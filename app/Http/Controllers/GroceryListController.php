<?php

namespace App\Http\Controllers;

use App\Models\QueryRepositories\GroceryListRepository;
use App\Models\QueryRepositories\GroceryItemRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class GroceryListController extends Controller
{
    protected $groceryListRepository;
    protected $groceryItemRepository;
    private $userId;

    public function __construct(GroceryListRepository $groceryListRepository, GroceryItemRepository $groceryItemRepository)
    {
        $this->groceryListRepository = $groceryListRepository;
        $this->groceryItemRepository = $groceryItemRepository;
        $this->userId = Auth::user()->id;
    }

    // Fetch all grocery lists for the logged-in user
    public function getGroceryLists()
    {
        $lists = $this->groceryListRepository->getAllGroceryListsWithItems($this->userId);
        return response()->json($lists);
    }

    // Create or update a grocery list for the user
    public function addGroceryList(Request $request)
    {
        $title = $request->json('title');

        // Create a new grocery list for the user
        $groceryList = $this->groceryListRepository->addOrUpdateGroceryList($title, $this->userId);

        return response()->json([
            'message' => "Successfully created grocery list: {$groceryList->title}",
            'list' => $groceryList // Return the full list with its ID
        ]);
    }

    // Delete a grocery list
    public function deleteGroceryList(Request $request)
    {
        $listId = $request->json('list_id');
        $result = $this->groceryListRepository->deleteGroceryList($listId, $this->userId);

        if ($result) {
            return response()->json(['message' => "Successfully deleted grocery list."]);
        } else {
            return response()->json(['message' => "Failed to delete grocery list or not found."], Response::HTTP_NO_CONTENT);
        }
    }

    // Get a specific grocery list by its ID
    public function getGroceryListById(Request $request)
    {
        $listId = $request->query('list_id');
        $list = $this->groceryListRepository->getGroceryListById($listId, $this->userId);

        return response()->json($list ?: ['message' => 'List not found'], Response::HTTP_OK);
    }
}
