<div class="seo" data-seo-controller='<?php echo json_encode( $controller, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE ); ?>'>

	<div class="seo-preview">
		<div class="seo-wrap seo-wrap-title">
			<div class="seo-view seo-view-title"></div>
		</div>
		<a class="seo-wrap seo-wrap-url" data-modal="" href="<?php echo $controller['url']['edit']; ?>">
			<div class="seo-view seo-view-url"><?php echo $controller['url']['preview']; ?></div>
		</a>
		<div class="seo-wrap seo-wrap-description">
			<div class="seo-view seo-view-description"></div>
		</div>
	</div>

	<div class="seo-edit">
		<?php echo SeoCore::snippet('field-edit', $data = array(
			'type' => 'title',
			'field' => $field,
			'page' => $page,
			'controller' => $controller
		));
		?>
		<?php echo SeoCore::snippet('field-edit', $data = array(
			'type' => 'description',
			'field' => $field,
			'page' => $page,
			'controller' => $controller
		));
		?>
	</div>

	<textarea class="input seo-render" id="<?php echo $field->id(); ?>" name="<?php echo $field->name(); ?>"><?php echo $field->value(); ?></textarea>
</div>
