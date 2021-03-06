<?php
namespace App\Controller;
use phpDocumentor\Reflection\Types\String_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\Navigation;
use Symfony\Component\HttpFoundation\Request;

class NavbarController extends AbstractController{

	/**
	 *
	 * @param Navigation $nav
	 * @param string $current_route_name
	 *        	It is passed from "base.html.twig" template, to give the parent controller route name (since here, current route name would be in this controller, actually not defined)
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function index(Navigation $nav, string $current_route_name = null){
		// Get user roles
		$userRoles = $this->getUser() ? $this->getUser()->getRoles() : null;

		// Get menu links depending on user's roles
		$navbar_links = $nav->getNavBarLinks($userRoles);

		// pop the information about the defined home page from the array ($navbar_links)
		$homepage_link = [];
		foreach ($navbar_links as $ix => $item){
			if (!empty($item['is_homepage'])){
				$homepage_link = $item;
				unset($navbar_links[$ix]);
				break;
			}
		}

		return $this->render('navbar/index.html.twig', [
			'controller_name' => 'NavbarController',
			'navbar_links' => $navbar_links,
			'home_page' => $homepage_link,
			'current_route_name' => $current_route_name
		]);
	}
}
