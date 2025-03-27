<?php

namespace App\Http\Controllers;

use App\Models\QueryRepositories\GroceryItemRepository;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class GroceryItemController extends Controller
{
    protected $groceryItemRepository;

    public function __construct(GroceryItemRepository $groceryItemRepository)
    {
        $this->groceryItemRepository = $groceryItemRepository;
    }

    // Get all items for a specific grocery list
    public function getItemsForGroceryList(Request $request)
    {
        $listId = $request->query('list_id');
        $items = $this->groceryItemRepository->getItemsForGroceryList($listId);

        return response()->json($items);
    }

    // Add or update multiple items in a grocery list
    public function saveItems(Request $request)
    {
        $listId = $request->json('list_id');
        $items = $request->json('items');  // Array of items

        $addedItems = [];

        foreach ($items as $itemData) {
            $itemName = $itemData['item_name'];
            $isChecked = $itemData['is_checked'];
            $position = $itemData['position'];

            $item = $this->groceryItemRepository->addOrUpdateItem($listId, $itemName, $isChecked, $position);
            $addedItems[] = $item;
        }

        return response()->json([
            'message' => 'Successfully added/updated items.',
            'items' => $addedItems
        ]);
    }

    // Add or update an item in a grocery list
    public function addItem(Request $request)
    {
        $listId = $request->json('list_id');
        $name = $request->json('name');
        $isChecked = $request->json('is_checked');
        $position = $request->json('position');

        $item = $this->groceryItemRepository->addOrUpdateItem($listId, $name, $isChecked, $position);

        return response()->json(['message' => "Successfully added/updated item: $name", 'item' => $item]);
    }

    // Delete an item from a grocery list
    public function deleteItem($itemId)
    {
        $result = $this->groceryItemRepository->deleteItem($itemId);

        if ($result) {
            return response()->json(['message' => 'Successfully deleted grocery item']);
        } else {
            return response()->json(['message' => 'Failed to delete item or not found'], Response::HTTP_NO_CONTENT);
        }
    }

    // Update the checked status of a grocery item
    public function updateCheckedStatus(Request $request)
    {
        $itemId = $request->json('item_id');
        $isChecked = $request->json('is_checked');
        $result = $this->groceryItemRepository->updateItemCheckedStatus($itemId, $isChecked);

        if ($result) {
            return response()->json(['message' => 'Successfully updated item status']);
        } else {
            return response()->json(['message' => 'Failed to update item status or item not found'], Response::HTTP_BAD_REQUEST);
        }
    }

    // Reposition a grocery item
    public function repositionItem(Request $request)
    {
        $itemId = $request->json('item_id');
        $newPosition = $request->json('new_position');
        $result = $this->groceryItemRepository->repositionItem($itemId, $newPosition);

        if ($result) {
            return response()->json(['message' => 'Successfully repositioned item']);
        } else {
            return response()->json(['message' => 'Failed to reposition item'], Response::HTTP_BAD_REQUEST);
        }
    }
}
