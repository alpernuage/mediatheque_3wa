<?php
namespace App\Service\Entity;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\StockableMedia;
use App\Entity\StateOfMedia;
use App\Entity\StockableMediaCopy as StockableMediaCopyEntity;

class StockableMediaCopy{

	/**
	 *
	 * @var Doctrine\Persistence\ManagerRegistry;
	 */
	private $doctrine;

	public function __construct(ManagerRegistry $doctrine){
		$this->doctrine = $doctrine;
	}

	public function generateCopyFromMedia(StockableMedia $stockableMedia){
		// Dès lors que l'on modifie la valeur de "stock", on doit impacter le nombre d'exemplaires présents dans la médiathèque
		// Par exemple, si on indique que la quantité "stock" d'un livre est égal à 5,
		// alors il doit y avoir 5 exemplaires de ce même livre dans la table "stockable_media_copy"
		// On récupère le vrai nombre d'exemplaires présents dans la médiathèque
		$realStock = $stockableMedia->getStockableMediaCopies()->count();
		$propStock = $stockableMedia->getStock();
		if ($propStock > $realStock){
			// Cas où l'on ajoute 1 ou plusieurs exemplaires (car la valeur de stock a augmenté)

			// D'abord, récupérer l'objet d'instance "MediaState" correspondant à l'ID 3 (état neuf)
			$media_state = $this->doctrine->getRepository(StateOfMedia::class)->findOneBy([
				'id' => 3
			]);
			// Ajout de la différence de média
			for ($i = $realStock + 1; $i <= $propStock; $i++){
				$newStockableMediaCopy = new StockableMediaCopyEntity();
				// La propriété "copy_number" définit donc le numéro de l'exemplaire
				$newStockableMediaCopy->setCopyNumber($i);
				// cette nouvelle instance de "StockableMediaCopy" est liée l'instance "$this" de "StockableMedia"
				$newStockableMediaCopy->setStockableMedia($stockableMedia);
				// Enfin, on applique l'état "neuf" à cette nouvelle copie, considérant que par défaut, un nouvel exemplaire est présupposément neuf
				$newStockableMediaCopy->setMediaState($media_state);
				// Utilisation de l'entity manager de l'objet "doctrine" qui a été injecté (injection de dépendance) dans l'objet "Media"
				$this->doctrine->getManager()->persist($newStockableMediaCopy);
				$stockableMedia->addStockableMediaCopy($newStockableMediaCopy);
			}
		} elseif ($propStock < $realStock){
			foreach ($stockableMedia->getStockableMediaCopies() as $stockableMediaCopy){
				if ($stockableMediaCopy->getCopyNumber() > $propStock)
					$stockableMedia->removeStockableMediaCopy($stockableMediaCopy);
			}
		}
	}
}