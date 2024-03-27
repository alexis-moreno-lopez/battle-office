<?php
namespace App\Controller;

use App\Entity\Commande;
use App\Entity\Payment;
use App\Form\CommandeType;
use App\Repository\ProductRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LandingPageController extends AbstractController
{
    #[Route('/', name: 'landing_page')]
    public function index(Request $request, EntityManagerInterface $entityManager, ProductRepository $productRepository): Response
    {
        // Créer une nouvelle instance de Commande et créer le formulaire
        $commande = new Commande();
        $formCommande = $this->createForm(CommandeType::class, $commande);
        $formCommande->handleRequest($request);
        $products = $productRepository->findAll();

        // Récupérer tous les produits


        // Traiter le formulaire s'il est soumis et valide
        if ($formCommande->isSubmitted() && $formCommande->isValid()) {
            $delivery=$formCommande->get('deliveryLocations')->getData();
            $productId = $request->get('order')['cart']['cart_products'][0];
            $request->get('order')['payment_method'];

            $product=$productRepository->find($productId);
            $commande->setProduct($product);
            // dd($delivery);

            $payment = new Payment();
            $payment->setStatus(0);
            $payment->setAmount($commande->getProduct()->getPrice());
            $payment->setCreatedAt(new DateTimeImmutable());
            $commande->setPayment($payment);

            $entityManager->persist($commande);
            $entityManager->persist($payment);
            $entityManager->persist($delivery);
            $entityManager->flush();

            $this->sendApiRequest($commande);
            return $this->redirectToRoute('confirmation');

            if($paymentMethod->getName() === 'stripe') {
                return $this->redirectToRoute('stripe_checkout', ['id' => $commande->getId()]);
            }

        }

        return $this->render('landing_page/index_new.html.twig', [
            'form'=>$formCommande->createView(),
            'products' => $products,
        ]);


    }


    #[Route('/confirmation', name: 'confirmation')]
    public function confirmation(Commande $commande): Response
    {
        return $this->render('landing_page/confirmation.html.twig');
    }

    #[Route('/stripe-checkout/{id}', name: 'stripe_checkout')]
    public function stripeCheckout(Commande $commande): RedirectResponse {
        \Stripe\stripe::setApiKey('sk_test_51Owl6FKZ5p3brdZFsStc5BRt7cwB0Y4DAt5CPw9Mzc44MKA90uyu1XyfH3eU8x5rplremcreo0U0fYdVUYKdpg5f00CmAPeQrY');
header('Content-Type: application/json');

$YOUR_DOMAIN = 'http://127.0.0.1:8000/';

$checkout_session = \Stripe\Checkout\Session::create([
    'customer_email' => $commande->getEmail(),
    'payment_method_types' => ['card'],
  'line_items' => [[
    # Provide the exact Price ID (e.g. pr_1234) of the product you want to sell
    'price_data' => [
       'currency' => 'eur', 
       'product_data' => [
        'name' => $commande->getProduct()->getName(),
        
       ],
         'unit_amount' => $commande->getProduct()->getPrice(),
    ],
    'quantity' => 1,
  ]],
  'mode' => 'payment',
  'success_url' => $YOUR_DOMAIN . 'confirmation/' . $commande->getId(),
  'cancel_url' => $YOUR_DOMAIN . 'cancel',
  'automatic_tax' => [
    'enabled' => true,
  ],

  
]);

             return new RedirectResponse($checkout_session->url);
    }

    public function sendApiRequest(Commande $commande)
    {

        $client = new \GuzzleHttp\Client();
        $client->request('POST', 'https://api-commerce.simplon-roanne.com/order', [

            'headers' => [
                'Authorization'=>"Bearer mJxTXVXMfRzLg6ZdhUhM4F6Eutcm1ZiPk4fNmvBMxyNR4ciRsc8v0hOmlzA0vTaX"
            ],
            'json' =>[


            'order' => [
                'id' => $commande->getId(),
                'product' => $commande->getProduct()->getName(),
                'payment_method' => "stripe",
                'status' => "WAITING",
                'client' => [
                'firstname' => $commande->getFirstName(),
                'lastname' => $commande->getlastName(),
                'email' => $commande->getEmail(),
                 ],
                'addresses' => [
                'billing' => [

                
                'address_line1' => $commande->getAdress(),
                'address_line2' => $commande->getAdressSup(),
                'city' => $commande->getCity(),
                'zipcode' => $commande->getCp(),
                'country' => $commande->getCountry(),
                'phone' => $commande->getPhone(),
                ],
                'shipping' => [
                'address_line1' => $commande->getDeliveryLocations()->getAdress(),
                'address_line2' => $commande->getDeliveryLocations()->getAdressSup(),
                'city' => $commande->getDeliveryLocations()->getCity(),
                'zipcode' => $commande->getDeliveryLocations()->getCp(),
                'country' => $commande->getDeliveryLocations()->getCountry(),
                'phone' => $commande->getDeliveryLocations()->getPhone(),  
                ]

 
             ]    
            ]
            ]
        ]);
    }
}
