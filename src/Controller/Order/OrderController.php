<?php

namespace App\Controller\Order;

use Stripe;
use DateTime;
use App\Entity\Address;
use App\Entity\Order\Order;
use App\Service\CartService;
use App\Form\Order\OrderType;
use App\Entity\Product\Custom;
use Symfony\Component\Mime\Email;
use App\Entity\Order\OrderDetails;
use App\Service\MondialRelayService;
use App\Repository\Front\ShopRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\Order\OrderRepository;
use App\Repository\Order\ShippingRepository;
use App\Repository\Product\CustomRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use App\Repository\Product\AttributRepository;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\Product\PromoCodeRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * This will suppress all the PMD warnings in
 * this class.
 *
 * @SuppressWarnings(PHPMD)
 */
class OrderController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/commande', name: 'order')]
    public function index(
        CartService $cart,
        ShippingRepository $shippingRepository,
        MondialRelayService $mondialRelayService
    ) {
        if ($this->getUser() == null) {
            $this->addFlash('warning', 'Vous devez être connecté pour passer une commande.');
            return $this->redirectToRoute('login');
        }

        $form = $this->createForm(OrderType::class, null, [
            'user' => $this->getUser()
        ]);

        return $this->render('order/index.html.twig', [
            'totalWeight' => $cart->getTotalWeight(),
            'shippingMethod' => $mondialRelayService->shipByTotWeight($cart),
            'form' => $form->createView(),
            'cart' => $cart->getFull(),
            'shipping' => $shippingRepository->findOneBy(['name' => $mondialRelayService->shipByTotWeight($cart)])
        ]);
    }

    #[Route('/commande/recapitulatif', name: 'order_recap', methods: ['POST'])]
    public function add(
        Request $request,
        CartService $cart,
        OrderRepository $orderRepository,
        CustomRepository $customRepository,
        ShippingRepository $shippingRepository,
        MondialRelayService $mondialRelayService,
        PromoCodeRepository $promoCodeRepository,
    ) {
        $form = $this->createForm(OrderType::class, null, [
            'user' => $this->getUser()
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $shipping = $form->get('shipping')->getData();
            $delivery = $form->get('addresses')->getData();
            $deliveryAddress = sprintf(
                '%s %s <br> %s <br> %s %s <br> %s',
                $delivery->getUser()->getFirstname(),
                $delivery->getUser()->getLastname(),
                $delivery->getAdresse(),
                $delivery->getZipcode(),
                $delivery->getCity(),
                $delivery->getCountry()
            );

            $adress = $form->get('addresses')->getData();
            if ($mondialRelayService->shipByTotWeight($cart) != 'Livraison gratuite') {
                $adress = new Address();
                $adress->setUser($this->getUser())
                    ->setName($form->get('name')->getData())
                    ->setAdresse($form->get('adresse')->getData())
                    ->setZipcode($form->get('zipCode')->getData())
                    ->setCity($form->get('city')->getData())
                    ->setCountry($form->get('country')->getData());
                $this->entityManager->persist($adress);
            } else {
                $orderRepository->createQueryBuilder('o')
                    ->update()
                    ->set('o.adress', ':adress')
                    ->where('o.adress IS NULL')
                    ->setParameter('adress', $delivery)
                    ->getQuery()
                    ->execute();
            }
            $reference = null;
            foreach ($cart->getFull() as $reference) {
                $reference = $reference['reference'];
            }
            $orders = $this->entityManager->getRepository(Order::class)->findBy([
                'user' => $this->getUser(),
            ]);
            $dayDate = new DateTime();
            $order = null;
            foreach ($orders as $key) {
                $order = $key;
            }

            if (!$orders || $order->getState() != 0) {
                $order = new Order();
                $order->setReference($reference)
                    ->setUser($this->getUser())
                    ->setCreatedAt($dayDate)
                    ->addShipping($shipping)
                    ->setAdress($adress)
                    ->setState(0);
                $this->entityManager->persist($order);
                foreach ($cart->getFull() as $product) {
                    $orderDetails = new OrderDetails();
                    $orderDetails->setMyOrder($order)
                        ->setProduct($product['product'])
                        ->setQuantity($product['quantity'])
                        ->setPrice($product['product']->getPrice())
                        ->setTaxe($product['product']->getTaxe()->getPercent())
                        ->setPrimaryOfferName($product['primaryOfferName'])
                        ->setPrimaryOfferReduce($product['primaryOfferReduce'])
                        ->setPrimaryOfferTypeReduce($product['primaryOfferTypeReduce'])
                        ->setSecondaryOfferName($product['secondaryOfferName'])
                        ->setSecondaryOfferReduce($product['secondaryOfferReduce'])
                        ->setSecondaryOfferTypeReduce($product['secondaryOfferTypeReduce'])
                        ->setCustomPrice($product['attribut']->getPrice())
                        ->setCustomDescription($product['description'])
                        ->setTotal($product['product']->getPrice() * $product['quantity']);
                    $this->entityManager->persist($orderDetails);
                }
            } else {
                $shippingRepository->createQueryBuilder('s')
                    ->update()
                    ->set('s.orderShipping', ':orderShipping')
                    ->setParameter('orderShipping', null)
                    ->getQuery()
                    ->execute();
                $order->addShipping($shipping)->setAdress($adress);
                $this->entityManager->persist($order);

                foreach ($cart->getFull() as $product) {
                    $orderDetails = new OrderDetails();
                    $orderDetails->setMyOrder($order)
                        ->setProduct($product['product'])
                        ->setQuantity($product['quantity'])
                        ->setPrice($product['product']->getPrice())
                        ->setTaxe($product['product']->getTaxe()->getPercent())
                        ->setPrimaryOfferName($product['primaryOfferName'])
                        ->setPrimaryOfferReduce($product['primaryOfferReduce'])
                        ->setPrimaryOfferTypeReduce($product['primaryOfferTypeReduce'])
                        ->setSecondaryOfferName($product['secondaryOfferName'])
                        ->setSecondaryOfferReduce($product['secondaryOfferReduce'])
                        ->setSecondaryOfferTypeReduce($product['secondaryOfferTypeReduce'])
                        ->setCustomPrice($product['attribut']->getPrice())
                        ->setCustomDescription($product['description'])
                        ->setTotal($product['product']->getPrice() * $product['quantity']);
                    $this->entityManager->persist($orderDetails);
                }
            }

            $orderDetails = $this->entityManager->getRepository(OrderDetails::class)->findBy([
                'myOrder' => $orders,
            ]);

            foreach ($orderDetails as $orderDetail) {
                if ($orderDetail->getMyOrder() == $order) {
                    $this->entityManager->remove($orderDetail);
                }
            }
            $this->entityManager->flush();

            $customRepository->createQueryBuilder('c')
                ->update()
                ->set('c.customOrder', ':order')
                ->where('c.customOrder IS NULL')
                ->setParameter('order', $order)
                ->getQuery()
                ->execute();

            $cartQuantity = null;
            for ($i = 0; $i < count($cart->getFull()); $i++) {
                $cartQuantity[] = $cart->getFull()[$i]['quantity'];
            }

            $customs = $customRepository->findBy([
                'customOrder' => $order,
            ]);

            $customQuantity = null;
            for ($i = 0; $i < count($customs); $i++) {
                $customQuantity[] = $customs[$i]->getQuantity();
            }

            return $this->render('order/add.html.twig', [
                'cart' => $cart->getFull(),
                'cartQuantity' => $cartQuantity,
                'customs' => $customs,
                'customQuantity' => $customQuantity,
                'shipping' => $shipping,
                'delivery_address' => $deliveryAddress,
                'reference' => $order->getReference(),
                'stripe_key' => $_ENV["STRIPE_KEY"],
                'total' => $cart->getTotal(),
                'order' => $order,
                'promo_codes' => $promoCodeRepository->findByIsValidated(),
            ]);
        }
        return $this->redirectToRoute('cart');
    }

    #[Route('/stripe/create-charge/', name: 'stripe_charge', methods: ['POST'])]
    public function createCharge(
        Request $request,
        CartService $cart,
        MailerInterface $mailer,
        ShopRepository $shopRepository,
        OrderRepository $orderRepository,
        AttributRepository $attributRepository
    ) {

        $shop = $shopRepository->findOneBy(['isActive' => true]);

        $cart->getTotal();

        Stripe\Stripe::setApiKey($_ENV["STRIPE_SECRET"]);
        Stripe\Charge::create([
            'amount' => $cart,
            "currency" => "eur",
            'description' => $shop->getName(),
            "source" => $request->request->get('stripeToken'),
        ]);

        $order = $this->entityManager->getRepository(Order::class)
            ->findOneBy(['user' => $this->getUser()], ['createdAt' => 'DESC']);

        $getEmail = $this->entityManager->getRepository(Order::class)
            ->findOneBy(['user' => $this->getUser()]);

        $command = $orderRepository->findLastOrder();
        $command->setState(1);
        $this->entityManager->persist($order);
        $this->entityManager->flush();

        for ($i = 0; $i < count($cart->getFull()); $i++) {
            $attribut = $attributRepository->findOneBy(['id' => $cart->getFull()[$i]['attribut']->getId()]);
            $attribut->setQuantity($attribut->getQuantity() - $cart->getFull()[$i]['quantity']);
            $this->entityManager->persist($attribut);
            $this->entityManager->flush();
        }

        $email = (new Email())
            ->to($this->getParameter('mailer_address'))
            ->from($this->getParameter('mailer_address'))
            ->subject('Nouvelle commande')
            ->html($this->renderView('mailer/order.html.twig', [
                'cart' => $cart->getFull(),
                'total' => $cart->getTotal(),
                'shop' => $shop,
                'order' => $order,
                'address' => $order->getAdress(),
            ]));
        $mailer->send($email);

        $emailClient = (new Email())
            ->to($getEmail->getUser()->getEmail())
            ->from($this->getParameter('mailer_address'))
            ->subject('Confirmation de commande')
            ->html($this->renderView('mailer/recap.html.twig', [
                'cart' => $cart->getFull(),
                'total' => $cart->getTotal(),
                'shop' => $shop,
                'order' => $order,
                'address' => $order->getAdress(),
            ]));
        $mailer->send($emailClient);

        $this->addFlash(
            'success',
            'Paiement Réussi!'
        );
        $cart->remove();
        return $this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER);
    }
}
