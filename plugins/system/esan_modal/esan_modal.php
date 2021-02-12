<?php

class plgSystemEsan_Modal extends JPlugin {

	public function onBeforeCompileHead () {
		if (JFactory::getApplication()->isAdmin()){
			return;
		}

		$base = JUri::root(true) . '/plugins/system/esan_modal';

		$doc = JFactory::getDocument();
		$doc->addStyleSheet("{$base}/assets/ESANModal.css");
		$doc->addScript("{$base}/assets/ESANModal.js");
	}

}
