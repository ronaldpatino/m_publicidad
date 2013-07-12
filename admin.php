<div class="wrap">
<?php screen_icon(); ?>
<h2>M_Publicidad</h2>

    <?php   
        if(isset($_POST['m_publicidad_post']) && $_POST['m_publicidad_post'] == 'Y') {  
            
			
        } else {  
		?>
			<form method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>"> 
				<input type="hidden" name="m_publicidad_post" value="Y">  
				<table class="form-table">
				<tr valign="top">
					<th scope="row">	
							<label for="m_publicidad_nombre_banner">Nombre del Banner:</label>		
					</th>
					<td>
						<input type="text" id="m_publicidad_nombre_banner" name="m_publicidad_nombre_banner" value="<?php echo isset($m_publicidad_nombre_banner)?$m_publicidad_nombre_banner:''; ?>" class="regular-text">
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="m_publicidad_ancho_banner">Ancho en pixels del Banner:</label>		
					</th>
					<td>
						<input type="text" id="m_publicidad_ancho_banner" name="m_publicidad_ancho_banner" value="<?php echo isset($m_publicidad_ancho_banner)?$m_publicidad_ancho_banner:''; ?>" class="regular-text">
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="blogname">Alto en pixels del Banner:</label>				
					</th>
					<td>
						<input type="text" id="m_publicidad_alto_banner" name="m_publicidad_alto_banner" value="<?php echo isset($m_publicidad_alto_banner)?$m_publicidad_alto_banner:''; ?>" class="regular-text">
					</td>
				</tr>
				</table>

				<?php submit_button(); ?>
			</form>
			<hr />  
	<?php
        }  
    ?>  


</div>