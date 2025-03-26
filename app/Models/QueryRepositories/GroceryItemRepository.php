<?php

namespace App\Models\QueryRepositories;

use App\Models\GroceryItem;
use App\Models\GroceryList;
use Illuminate\Support\Facades\DB;

class GroceryItemRepository
{
    /**
     * Get all items for a specific grocery list.
     * @param int $listId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getItemsForGroceryList($listId)
    {
        return GroceryList::find($listId)
            ->items()
            ->get();
    }

    /**
     * Add or update an item in a grocery list.
     * @param int $listId
     * @param string $name
     * @param bool $isChecked
     * @param int $position
     * @return GroceryItem
     */
    public function addOrUpdateItem($listId, $itemName, $isChecked, $position)
    {
        return GroceryItem::updateOrCreate(
            ['grocery_list_id' => $listId, 'item_name' => $itemName],
            ['name' => $itemName, 'is_checked' => $isChecked, 'position' => $position, 'grocery_list_id' => $listId]
        );
    }

    /**
     * Delete a grocery item.
     * @param int $itemId
     * @return int
     */
    public function deleteItem($itemId)
    {
        return GroceryItem::where('id', $itemId)
            ->delete();
    }

    /**
     * Update the checked status of an item.
     * @param int $itemId
     * @param bool $isChecked
     * @return bool
     */
    public function updateItemCheckedStatus($itemId, $isChecked)
    {
        $item = GroceryItem::find($itemId);
        if ($item) {
            $item->is_checked = $isChecked;
            return $item->save();
        }

        return false;
    }

    /**
     * Reposition a grocery item.
     * @param int $itemId
     * @param int $newPosition
     * @return bool
     */
    public function repositionItem($itemId, $newPosition)
    {
        $item = GroceryItem::find($itemId);
        if ($item) {
            $item->position = $newPosition;
            return $item->save();
        }

        return false;
    }
}
