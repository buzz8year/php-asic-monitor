<?php
namespace App\Bootstrap3\Helpers\Elements;

use App\Views\BaseView;
use App\Views\ViewInterface;

class Checkboxes extends BaseView implements ViewInterface
{
    /**
     * Identifier for the checkboxes block
     * @var string
     */
    public $id;
    
    /**
     * Title/label
     * @var string
     */
    public $title;
    
    /**
     * Contains checkbox objects for rendering
     * @var Checkbox[]
     */
    protected $checkboxes;

    /**
     * Constructor
     * @param array|null $checkboxes
     * @param array|null $attributes
     */
    public function __construct(?array $checkboxes = null, ?array $attributes = null)
    {
        if (! \is_null($checkboxes) ) {
            
            foreach ($checkboxes as $checkbox) {
                
                $this->addCheckbox($checkbox);
            }
        }
        
        if (! \is_null($attributes)) {
            
            foreach (array("title") as $attribute)
            {
                if (isset($attributes[$attribute]))
                {
                    $this->$attribute = $attributes[$attribute];
                }
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

    /**
     * 
     * @return Checkbox[]
     */
    public function getCheckboxes()
    {
        return $this->checkboxes;
    }

    public function setCheckboxes(array $checkboxes)
    {
        $this->checkboxes = $checkboxes;
        return $this;
    }
    
    public function addCheckbox(Checkbox $checkbox)
    {
        $this->checkboxes[] = $checkbox;
        return $this;
    }
    

    /**
     * Render output
     */
    public function out()
    {
        ?>
			<div class="form-group">
        		
        		<label class="control-label col-sm-3"><?php print $this->getTitle();?></label>
        		<div class="col-sm-9">
        			<div id="<?php print $this->getId()?>">
            			<?php foreach ($this->getCheckboxes() as $checkbox):?>
            				<?php $checkbox->out()?>
            			<?php endforeach;?>
            		</div>
        		</div>
        		
        	</div>
         <?php         
        }
}