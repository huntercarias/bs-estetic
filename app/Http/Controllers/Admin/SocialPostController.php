<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SocialAccount;
use App\Models\SocialPost;
use App\Services\Social\SocialPublisherService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SocialPostController extends Controller
{
    // ── Publicaciones ────────────────────────────────────────────────

    public function index()
    {
        $posts = SocialPost::where('tenant_id', auth()->user()->tenant_id)
            ->with('author')
            ->latest()
            ->paginate(15);

        return view('admin.social-posts.index', compact('posts'));
    }

    public function create()
    {
        $accounts = SocialAccount::where('tenant_id', auth()->user()->tenant_id)
            ->get()
            ->keyBy('platform');

        return view('admin.social-posts.create', compact('accounts'));
    }

    public function store(Request $request, SocialPublisherService $publisher)
    {
        $request->validate([
            'body'      => 'required|string|max:5000',
            'title'     => 'nullable|string|max:200',
            'platforms' => 'required|array|min:1',
            'platforms.*' => 'in:facebook,instagram,whatsapp,tiktok',
            'image'     => 'nullable|image|max:10240',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('social-posts', 'public');
        }

        $post = SocialPost::create([
            'tenant_id'  => auth()->user()->tenant_id,
            'user_id'    => auth()->id(),
            'title'      => $request->title,
            'body'       => $request->body,
            'image_path' => $imagePath,
            'platforms'  => $request->platforms,
            'status'     => 'draft',
        ]);

        $publisher->publish($post);

        return redirect()->route('admin.social-posts.show', $post)
            ->with('success', 'Publicación procesada correctamente.');
    }

    public function show(SocialPost $socialPost)
    {
        abort_if($socialPost->tenant_id !== auth()->user()->tenant_id, 403);

        return view('admin.social-posts.show', compact('socialPost'));
    }

    public function destroy(SocialPost $socialPost)
    {
        abort_if($socialPost->tenant_id !== auth()->user()->tenant_id, 403);

        if ($socialPost->image_path) {
            Storage::disk('public')->delete($socialPost->image_path);
        }

        $socialPost->delete();

        return redirect()->route('admin.social-posts.index')
            ->with('success', 'Publicación eliminada.');
    }

    // ── Configuración de cuentas ─────────────────────────────────────

    public function settings()
    {
        $tenantId = auth()->user()->tenant_id;

        $platforms = ['facebook', 'instagram', 'whatsapp', 'tiktok'];
        $accounts  = SocialAccount::where('tenant_id', $tenantId)->get()->keyBy('platform');

        // Crear registros vacíos para plataformas sin configurar
        foreach ($platforms as $p) {
            if (!$accounts->has($p)) {
                $accounts[$p] = new SocialAccount(['platform' => $p, 'active' => false]);
            }
        }

        return view('admin.social-posts.settings', compact('accounts'));
    }

    public function saveSettings(Request $request)
    {
        $request->validate([
            'facebook.page_id'      => 'nullable|string|max:100',
            'facebook.access_token' => 'nullable|string|max:500',
            'facebook.account_name' => 'nullable|string|max:100',
            'instagram.ig_user_id'  => 'nullable|string|max:100',
            'instagram.access_token'=> 'nullable|string|max:500',
            'instagram.account_name'=> 'nullable|string|max:100',
        ]);

        $tenantId = auth()->user()->tenant_id;

        foreach (['facebook', 'instagram', 'whatsapp', 'tiktok'] as $platform) {
            $data = $request->input($platform, []);

            $fill = [
                'account_name' => $data['account_name'] ?? null,
                'active'       => isset($data['active']),
            ];

            if ($platform === 'facebook') {
                $fill['page_id'] = $data['page_id'] ?? null;
                if (!empty($data['access_token'])) {
                    $fill['access_token'] = $data['access_token'];
                }
            }

            if ($platform === 'instagram') {
                $fill['ig_user_id'] = $data['ig_user_id'] ?? null;
                if (!empty($data['access_token'])) {
                    $fill['access_token'] = $data['access_token'];
                }
            }

            if (in_array($platform, ['whatsapp', 'tiktok'])) {
                $fill['active'] = isset($data['active']);
            }

            SocialAccount::updateOrCreate(
                ['tenant_id' => $tenantId, 'platform' => $platform],
                $fill
            );
        }

        return redirect()->route('admin.social-posts.settings')
            ->with('success', 'Configuración guardada correctamente.');
    }
}
