<?php

class headSeoTagsRobots_adminOption1 {
	public static function getSeoTags(){
		return [
			'all',
			'none',
			'index',
			'noindex',
			'follow',
			'nofollow',
			'snippet',
			'nosnippet',
			'archive',
			'noarchive',
			'translate',
			'notranslate',
			'imageindex',
			'noimageindex',
			'odp',
			'noodp',
		];
	}
	public static function getSeoTagsBehaviour(){
		#['all','none','index','noindex','follow','nofollow','snippet','nosnippet','archive','noarchive','translate','notranslate','imageindex','noimageindex','odp','noodp',]
		return [
			'all'=>[
				'locks'     =>['none','index','noindex','follow','nofollow','snippet','nosnippet','archive','noarchive','translate','notranslate','imageindex','noimageindex','odp','noodp',],
				'selected'  =>['index','follow','snippet','archive','translate','imageindex','odp',],
				'deselected'=>['none','noindex','nofollow','nosnippet','noarchive','notranslate','noimageindex','noodp',],
			],
			'none'=>[
				'locks'     =>['all','index','noindex','follow','nofollow','snippet','nosnippet','archive','noarchive','translate','notranslate','imageindex','noimageindex','odp','noodp',],
				'selected'  =>['noindex','nofollow','nosnippet','noarchive','notranslate','noimageindex','noodp',],
				'deselected'=>['all','index','follow','snippet','archive','translate','imageindex','odp',],
			],
			'index'=>[
				'locks'     =>['noindex',],
				'selected'  =>[],
				'deselected'=>['noindex',],
			],
			'noindex'=>[
				'locks'     =>['index','snippet','nosnippet','archive','noarchive','imageindex','noimageindex',],
				'selected'  =>['nosnippet','noarchive','noimageindex',],
				'deselected'=>['index','snippet','archive','imageindex',],
			],
			'follow'=>[
				'locks'     =>['nofollow',],
				'selected'  =>[],
				'deselected'=>['nofollow',],
			],
			'nofollow'=>[
				'locks'     =>['follow',],
				'selected'  =>[],
				'deselected'=>['follow',],
			],
			'snippet'=>[
				'locks'     =>['nosnippet',],
				'selected'  =>[],
				'deselected'=>['nosnippet',],
			],
			'nosnippet'=>[
				'locks'     =>['snippet',],
				'selected'  =>[],
				'deselected'=>['snippet',],
			],
			'archive'=>[
				'locks'     =>['noarchive',],
				'selected'  =>[],
				'deselected'=>['noarchive',],
			],
			'noarchive'=>[
				'locks'     =>['archive',],
				'selected'  =>[],
				'deselected'=>['archive',],
			],
			'translate'=>[
				'locks'     =>['notranslate',],
				'selected'  =>[],
				'deselected'=>['notranslate',],
			],
			'notranslate'=>[
				'locks'     =>['translate',],
				'selected'  =>[],
				'deselected'=>['translate',],
			],
			'imageindex'=>[
				'locks'     =>['noimageindex',],
				'selected'  =>[],
				'deselected'=>['noimageindex',],
			],
			'noimageindex'=>[
				'locks'     =>['imageindex',],
				'selected'  =>[],
				'deselected'=>['imageindex',],
			],
			'odp'=>[
				'locks'     =>['noodp',],
				'selected'  =>[],
				'deselected'=>['noodp',],
			],
			'noodp'=>[
				'locks'     =>['odp',],
				'selected'  =>[],
				'deselected'=>['odp',],
			],
		];
	}
	public static function render_AdminCP_CustomLinksAdder(XenForo_View $view, $fieldPrefix, array $preparedOption, $canEdit){
		$seoTags = static::getSeoTags();
		$t = $preparedOption['option_value'];
		//die(print_r($t,true));
		$choices = [];
		foreach($t as $template=>$rules){
			$choices[$template] = [];
			foreach($seoTags as $seotag){
				$v = false;
				if(isset($rules[$seotag])){
					$v = boolval($rules[$seotag]);
				}
				$choices[$template][$seotag] = $v;
			}
		}
		//die(print_r($choices,true));
		ksort($choices);
		$editLink = $view->createTemplateObject('option_list_option_editlink', array(
			'preparedOption' => $preparedOption,
			'canEditOptionDefinition' => $canEdit
		));
		
		$beh = static::getSeoTagsBehaviour();
		
		/*
		homeOrServer_DownloadHelper::sendAsDownload(
		json_encode(
		$choices
		,JSON_PRETTY_PRINT)
		,'a','',false);
		//*/
		$robotstxtloc = dirname(__FILE__);
		$robotstxtloc = explode('/',$robotstxtloc);
		array_push($robotstxtloc,'robots.txt');
		$robotstxtloc = implode('/',$robotstxtloc);
		$robotstxt = '';
		while(strlen($robotstxt)==0){
			$robotstxtloc = explode('/',$robotstxtloc);
			array_pop($robotstxtloc);
			array_pop($robotstxtloc);
			if(count($robotstxtloc)<=2){
				/**
				 * A site that is too shallow in the filesystem will
				 *  have problems with this addon:
				 *  Example: /domain.com/robots.txt
				 *    ["","domain.com"] --> exit-on-fail condition met
				 */
				$robotstxtloc = '';
				break;
			}
			array_push($robotstxtloc,'robots.txt');
			$robotstxtloc = implode('/',$robotstxtloc);
			try{
				$robotstxt = file_get_contents($robotstxtloc);
			}catch(Exception $e){;};
		}
		
		/*
		homeOrServer_DownloadHelper::sendAsDownload(
		json_encode(
		$robotstxt
		,JSON_PRETTY_PRINT)
		,'a','',false);
		//*/
		
		return $view->createTemplateObject('kiror_option_template_custom_seo_robots_tag', array(
			'fieldPrefix' => $fieldPrefix,
			'listedFieldName' => $fieldPrefix . '_listed[]',
			'preparedOption' => $preparedOption,
			'formatParams' => $preparedOption['formatParams'],
			'editLink' => $editLink,
			
			'robotstxtloc' => $robotstxtloc,
			'robotstxt' => $robotstxt,
			'seotags' => $seoTags,
			'seotemplates' => array_keys($choices),
			'seotablebehaviour' => $beh,
			'seorobotchoices' => $choices,
			'nextCounter' => count($choices)
		));
	}
	
	public static function verifier_AdminCP_CustomLinksAdder(array &$templates, XenForo_DataWriter $dw, $fieldName){
		$output = array();
		$seoTags = static::getSeoTags();
		
		foreach ($templates AS $template=>$rules){
			$output[$template]=[];
			foreach ($seoTags AS $seotag){
				$v = false;
				if(isset($rules[$seotag])){
					$v = boolval($rules[$seotag]);
				}
				$output[$template][$seotag] = $v;
			}
		}

		$templates = $output;

		return true;
	}
}
