<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Services\FavoriteService;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    protected FavoriteService $favoriteService;

    public function __construct(FavoriteService $favoriteService)
    {
        $this->favoriteService = $favoriteService;
        $this->middleware('auth');
    }

    /**
     * Get all favorites for authenticated user
     */
    public function index()
    {
        $favorites = $this->favoriteService->getFavorites(auth()->id());

        return view('favorites.index', [
            'favorites' => $favorites,
        ]);
    }

    /**
     * Toggle favorite for vehicle
     */
    public function toggle($vehicleId)
    {
        $this->favoriteService->toggleFavorite(auth()->id(), $vehicleId);

        $isFavorited = $this->favoriteService->isFavorited(auth()->id(), $vehicleId);

        return response()->json([
            'success' => true,
            'is_favorited' => $isFavorited,
            'message' => $isFavorited ? 'Added to favorites' : 'Removed from favorites',
        ]);
    }

    /**
     * Check if vehicle is favorited
     */
    public function check($vehicleId)
    {
        $isFavorited = $this->favoriteService->isFavorited(auth()->id(), $vehicleId);

        return response()->json(['is_favorited' => $isFavorited]);
    }

    /**
     * Remove favorite
     */
    public function remove($favoriteId)
    {
        $favorite = Favorite::findOrFail($favoriteId);

        if ($favorite->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $favorite->delete();

        return response()->json(['success' => true, 'message' => 'Removed from favorites']);
    }

    /**
     * Clear all favorites
     */
    public function clearAll()
    {
        Favorite::where('user_id', auth()->id())->delete();

        return response()->json(['success' => true, 'message' => 'All favorites cleared']);
    }
}
