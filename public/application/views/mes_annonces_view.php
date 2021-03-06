<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<!DOCTYPE html>

<!-- Container Boostrap -->
<div class="container">

	<?php
		$this->load->view('elements/filter')
	?>
		
	</header>
	<!-- Row Page -->
	<div class="row text-center">
		<?php
			if (count($mes_annonces)>0){
				foreach($mes_annonces as $annonce){
					$id_ann = $annonce["id_annonce"];
					$images = $this->image->getImage($id_ann);
					echo '<div class="col-lg-3 col-md-6 mb-4 annonce">';
					echo '<input type="hidden" value="'.$annonce["prix"].'">';
					echo '<p hidden class="categorie">'.implode(" ",$annonce["categories"]).' </p>';													


						echo '<div class="card mb-4 box-shadow">';
						if($images==null)
							echo '<a href="#"><img class="card-img-top" src="'.base_url().'/assets/images/default.jpg" width="600" height="200" alt=""></a>';
						else if($images[0]['url']==""|| !file_exists('assets/images/'.$images[0]['url']))
							echo '<a href="#"><img class="card-img-top" src="'.base_url().'/assets/images/default.jpg" width="600" height="200" alt=""></a>';
						else
							echo '<a href="#"><img class="card-img-top" src="'.base_url().'/assets/images/'.$images[0]['url'].'" width="600" height="200" alt=""></a>';

						echo '<div class="card-body">';
								echo '<p class="card-title">';
									echo '<a href="'.site_url('/Annonce/details_annonce/'.$annonce["id_annonce"]).'">'.$annonce["titre"].'</a>';
                                echo '</p>';
                                echo '<div class="d-flex justify-content-between align-items-center">';
								echo '<div class="btn-group">';
						?>
								<button type="button" class="btn btn-sm btn-outline-warning" onclick="window.location.replace('<?php echo site_url('/Annonce/modifier_annonce/'.$annonce['id_annonce']); ?>');">Modifier</button>
								<button type="button" class="btn btn-sm btn-outline-danger" data-toggle="modal" data-target="#suppressionModal<?php echo $annonce['id_annonce'];?>">Supprimer</button>
						<?php
                                echo '</div>';
								echo '<div class="d-flex justify-content-between align-items-center">';
									echo '<p class="h6">'.$annonce["prix"].'€</p>';
                                echo '</div>';                                
                                echo '</div>';									
							echo '</div>';
						echo '</div>';
					echo '</div>';

					?>
						<!-- The Modal -->
						<div class="modal fade" id="suppressionModal<?php echo $annonce['id_annonce'];?>" >
							<div class="modal-dialog">
							<div class="modal-content">

								<!-- Modal Header -->
								<div class="modal-header text-white bg-danger">
								<h4 class="modal-title">Supprimer annonce</h4>
								<button type="button" class="close" data-dismiss="modal">&times;</button>
								</div>
								
								<!-- Modal body -->
								<div class="modal-body">
								&Ecirctes-vous sûr de vouloir supprimer cette annonce ?
								</br>Cette action est irreversible.
								</div>
								
								<!-- Modal footer -->
								<div class="modal-footer">								
									<button type="button" class="btn btn-danger" onclick="window.location.replace('<?php echo site_url('/Annonce/supprimer_annonce/'.$annonce['id_annonce']); ?>');">Supprimer</button>
									<button type="button" class="btn btn-secondary " data-dismiss="modal" aria-hidden="true">Annuler</button>
								</div>
								
							</div>
							</div>
						</div>
						
					<?php
				}
			}
		?>

	</div>
	<!-- /.row -->
</div>
<!-- /.container -->