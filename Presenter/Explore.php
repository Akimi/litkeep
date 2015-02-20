<?php
namespace Presenter;
use Vendor\Pattern as Pattern;
use Model;
use Vendor;
use Component;

class Explore extends Pattern
{
	/**
	 * Model\Version
	 * @access private
	 */
	private $version;

	/**
	 * Vendor\System
	 * @access private
	 */
	private $system;

	/**
	 * Model\Comment
	 * @access private
	 */
	private $comment;

	/**
	 * Component\Comment;
	 * @access private
	 */
	private $Comment;

	/**
	 * Konstruktor
	 * @access public
	 * @return void
	 */
	public function start()
	{
		$this->version = new Model\Version;
		$this->system = new Vendor\System;
		$this->comment = new Model\Comment;
		$this->Comment = new Component\Comment;
	}

	/**
	 * Provedeme akce
	 * @access public
	 * @return void
	 */
	public function actionDefault()
	{
		$this->articleByUrl = $this->version->getByUrl( $this->var["url"] )->fetch();
		$this->Comment->actionForm( $this->articleByUrl["article_id"] );
	}

	/**
	 * Vykreslení pohledu
	 * @access public
	 * @return void
	 */
	public function renderDefault()
	{
		$children = $this->version->getByParent( $this->articleByUrl["article_id"] )->fetchAll();
		$parent = $this->version->getParent( $this->articleByUrl["parent"] )->fetch();

		$if = $this->var["url"] == "root" ? True : False;

		if( $this->articleByUrl ) {
			$this->data["article"] = $this->articleByUrl;
			$this->data["parent"] = $parent;
			$this->data["children"] = $children;
			$this->data["if"] = $if;
			$this->data["comment"] = $this->Comment;
			$this->data["type"] = "read";
			$this->renderView("menubar");
			$this->renderView("explore/default");
		} else {
			$this->system->error404();
		}
	}
}