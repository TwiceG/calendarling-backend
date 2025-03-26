<?php

namespace App\Models\QueryRepositories;

use App\Models\GroceryList;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class GroceryListRepository
{
    /**
     * Get all grocery lists for a specific user.
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getGroceryLists($userId)
    {
        return User::find($userId)
            ->groceryLists()
            ->get();
    }

    /**
     * Create or update a grocery list for a user by ID.
     * If no ID is passed, return null.
     * @param string $title
     * @param int $userId
     * @param int|null $id
     * @return GroceryList|null
     */
    public function addOrUpdateGroceryList($title, $userId, $listId = null)
    {
        if (!$listId) {
            return null;
        }
        return GroceryList::updateOrCreate(
            ['user_id' => $userId, 'id' => $listId], // to find the list
            ['title' => $title, 'user_id' => $userId] // Update or create
        );
    }

    /**
     * Delete a grocery list for a user.
     * @param int $listId
     * @param int $userId
     * @return int
     */
    public function deleteGroceryList($listId, $userId)
    {
        return GroceryList::where('id', $listId)
            ->where('user_id', $userId)
            ->delete();
    }

    /**
     * Get a single grocery list by its ID along with its items.
     * @param int $listId
     * @param int $userId
     * @return GroceryList|null
     */
    public function getGroceryListById($listId, $userId)
    {
        return GroceryList::with('items')  // Eager load the associated items
            ->where('id', $listId)
            ->where('user_id', $userId)
            ->first();
    }

    /**
     * Get all grocery lists with their associated items for a user.
     *
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllGroceryListsWithItems($userId)
    {
        return GroceryList::with('items')
            ->where('user_id', $userId)
            ->get();
    }
}
