<?php 

namespace App\Service;

use App\Service\CartService;
use App\Repository\ProductRepository;
use App\Repository\ProduitRepository;
use Symfony\Component\HttpFoundation\RequestStack;

class CartService
{
    private $repo;
                        //* creer son panier
    private $rs;

    //* Inyeccion de dependencias en nuestro controlador: "__constructor"
    //* em construictor se declanche cuando instanciamos la clase, es decir cuando CartService es instanciada
    //* $this hace referencia al objet courant, y self:: hace referencia a la clase
    public function __construct(ProduitRepository $repo, RequestStack $rs) {
        
        $this->rs = $rs;

        $this->repo = $repo;
    }

    public function add($id) {
        //* Recuperamos o creamos una session gracias a la clase RequestStack (service)
        $session = $this->rs->getSession();

        //* Recupero el atributo de session 'cart' si existe o sino me devuelve un array vacio
        $cart = $session->get('cart', []);
        $qt = $session->get('qt', 0);
        
        //* Le doy valor de uno para inicializarlo
        if(!empty($cart[$id])){
            $cart[$id]++;
            $qt++;
        }  else {
            $cart[$id] = 1; 
            $qt++;      
            
        }
        $session->set('qt', $qt);        
        $session->set('cart', $cart);
    }

    public function remove($id) {
        $session = $this->rs->getSession();
        $cart = $session->get('cart', []);
        $qt = $session->get('qt', 0);

        if(!empty($cart[$id])) 
        {
            $qt -= $cart[$id];
            unset($cart[$id]);
        } if($qt < 0) {
            $qt =0;      
            
        }
        $session->set('qt', $qt);
        $session->set('cart', $cart);
    }

    public function cartWithData() 
    {
        $session = $this->rs->getSession();
        $cart = $session->get('cart', []);

        //* Creo un nuevo array que contendra los objetos Product y las cantidades de cada objeto
        $cartWithData = [];
        $total = 0;

        //* Para cada $id que se encuenytra en mi array $cart, le anado un caso $cartWithData
        //* En cada una de sus casilla existira un array asociativo que contengan 2 casos: 1 para product y otro para quantite
        foreach($cart as $id => $quantite) 
        {
            $produit = $this->repo->find($id);
            $cartWithData[] = [
                'produit' => $produit,
                'quantite' => $quantite, 
            ];
        }

        return $cartWithData;

    }

    public function total() {
        $cartWithData = $this->cartWithData();
        $total = 0;

        foreach($cartWithData as $item) 
        {
            $totalItem = $item['produit']->getPrix() * $item['quantite'];
            $total += $totalItem;
            //* Importante, aqui $total se incrementa en cada bucle con el +=
        }

    }
}