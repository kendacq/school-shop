<?php

namespace App\View\Components;

use Closure;
use App\Models\Item;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class ItemCard extends Component
{
    public Item $item;
    public $attributesList;
    /**
     * Create a new component instance.
     */
    public function __construct(Item $item)
    {
        $this->item = $item;

        $this->attributesList = [];
        foreach ($item->variants as $variant) {
            foreach ($variant->attributes as $key => $value) {
                $this->attributesList[$key][] = $value;
            }
        }
        foreach ($this->attributesList as $key => $values) {
            $this->attributesList[$key] = array_unique($values);
        }
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.item-card');
    }
}
