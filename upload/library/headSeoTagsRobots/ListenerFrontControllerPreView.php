<?php

class headSeoTagsRobots_ListenerFrontControllerPreView{
	public static function callback(
	  XenForo_FrontController $fc,
	  XenForo_ControllerResponse_Abstract &$controllerResponse,
	  XenForo_ViewRenderer_Abstract &$viewRenderer,
	  array &$containerParams){
		//try{
		if(class_exists('XenForo_ViewRenderer_HtmlPublic',false) && class_exists('XenForo_ControllerResponse_View',false)){
			if(($viewRenderer instanceof XenForo_ViewRenderer_HtmlPublic) && ($controllerResponse instanceof XenForo_ControllerResponse_View)){
				$templateName = $controllerResponse->templateName;
				$xfOpt = XenForo_Application::getOptions();
				$seoa = $xfOpt->get('seoHeaderTagsByKiror');
				if(!isset($seoa[$templateName])){
					$seoa[$templateName] = [];
					//Now needs updating the database
					$wrt = XenForo_DataWriter::create('XenForo_DataWriter_Option');
					$wrt->setExistingData('seoHeaderTagsByKiror');
					$wrt->set('option_value',serialize($seoa));
					$wrt->save();
					//Now needs updating XenForo_Option class
					$xfOpt->set('seoHeaderTagsByKiror', $templateName, []);
					//Both database and environment updated
				}
				//$seoa[$templateName] exists
				$seotags = [];
				foreach($seoa[$templateName] AS $seotag=>$wants){
					if($wants){
						$seotags[]=$seotag;
					}
				}
				$csts = implode(', ',$seotags);
				$cst = implode(',',$seotags);
				$controllerResponse->containerParams['kiror_seo_tags_count'] = count($seotags);
				$controllerResponse->containerParams['kiror_seo_tags_comma'] = $cst;
				$controllerResponse->containerParams['kiror_seo_tags_many'] = $seotags;
				header('X-Robots-Tag: '.$csts);
			}
		}
		//}catch(Error $e){die(print_r($e,true));};
	}
}
