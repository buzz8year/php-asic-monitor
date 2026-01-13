<?php
namespace App\Bootstrap3\Helpers\Elements;

use App\Views\BaseView;
use App\Views\ViewInterface;

class StaticText extends BaseView implements ViewInterface
{
    public $id;
    
    /**
     * Title/label
     * @var string
     */
    public $title;
    
    /**
     * Current value
     * @var string
     */
    public $value;
    
    /**
     * Constructor
     * @param array|null $options
     */
    public function __construct(?array $options = null)
    {
        foreach (array("id", "title", "value") as $attribute) {
            
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

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
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
    
    /**
     * Render output
     */
    public function out()
    {
        ?>
            	<div class="form-group">
    				<label class="control-label col-md-3"><?php print $this->getTitle()?></label>
        			<div class="col-sm-9">
        				<p class="form-control-static" id="<?php print $this->getId()?>"><?php print $this->getValue()?></p>
    				</div>
                    <div class="clearfix"></div>
    			</div>
    			
            <?php         
        }
 
    
    
}