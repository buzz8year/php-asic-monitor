<?php
namespace App\Bootstrap3\Helpers\Elements;

use App\Views\BaseView;
use App\Views\ViewInterface;

class Ckeditor extends BaseView implements ViewInterface
{
    public $id;
    
    /**
     * @var string
     */
    public $name;
    
    /**
     * @var string
     */
    public $title;
    
    /**
     * @var string
     */
    public $value;

    /**
     * @var boolean
     */
    public $require;
    
    /**
     * @var string
     */
    public $width = "100%";
    
    /**
     * @var string
     */
    public $height = "400px";
    
    /**
     * @var boolean
     */
    public $disabled = false;
    
    /**
     * @param array $options
     */
    public function __construct(array $options = null)
    {
        foreach (array("id", "name", "title", "require", "value", "disabled", "toolbar") as $attribute) {
            
            if (isset($options[$attribute])) {
                
                $this->$attribute = $options[$attribute];
            }
        }

        parent::__construct();
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    public function getRequire()
    {
        return $this->require;
    }

    public function setRequire($require)
    {
        $this->require = $require;
        return $this;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }
    
    public function getWigth()
    {
        return $this->width;
    }
    
    public function setWidth( $width )
    {
        $this->width = $width;
        return $this;
    }
    
    public function getHeight()
    {
        return $this->height;
    }
    
    public function setHeight( $height )
    {
        $this->height = $height;
        return $this;
    }
    
    public function getToolbar()
    {
        return $this->toolbar;
    }
    
    public function setToolbar( $toolbar )
    {
        $this->toolbar = $toolbar;
        return $this;
    }
    
    public function getDisabled()
    {
        return $this->disabled;
    }
    
    public function setDisabled( $disabled )
    {
        $this->disabled = $disabled;
        return $this;
    }
    
    /**
     * Вывод шаблона
     */
    public function out()
    {
        ?>
            	<div class="form-group">
    				<label class="control-label col-sm-3" for="<?php print $this->getId()?>"><?php print $this->getTitle()?></label>
    				<div class="col-sm-9"><?php 
    				
    				    /** Ckeditor output */
                        $fckForm = new \CKEditor($this->name);
                        $fckForm->basePath = "/ckeditor/";
                        $fckForm->returnOutput = true;
                        \CKFinder::SetupCKEditor($fckForm, '/ckfinder/');
                        
                        print $fckForm->editor(
                            
                            $this->getName(),
                            $this->getValue(),
                            array(
                                "width" => $this->getWigth(),
                                "height" => $this->getHeight(),
                                "toolbar" => $this->getToolbar()
                                )
                            );
                        ?>
    				</div>
    			</div>    			
            <?php         
        }
}