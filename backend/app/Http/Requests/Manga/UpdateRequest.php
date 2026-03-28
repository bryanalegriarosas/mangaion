<?php

namespace App\Http\Requests\Manga;

use App\Models\Manga;
use App\Models\Role;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user  = $this->user();
        $manga = $this->route('manga'); // route model binding
 
        // Super admin y admin pueden editar cualquier manga
        if ($user->hasRole(Role::SUPER_ADMIN) || $user->hasRole(Role::ADMIN)) {
            return true;
        }
 
        // El creador puede editar su propio manga
        return $manga instanceof Manga && $manga->created_by === $user->id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title'        => ['sometimes', 'string', 'max:255'],
            'description'  => ['nullable', 'string'],
            'author'       => ['nullable', 'string', 'max:255'],
            'artist'       => ['nullable', 'string', 'max:255'],
            'release_year' => ['nullable', 'integer', 'min:1900', 'max:' . date('Y')],
            'status'       => ['sometimes', 'string', Rule::in(['ongoing', 'completed', 'hiatus', 'cancelled'])],
            'type'         => ['sometimes', 'string', Rule::in(['manga', 'manhwa', 'manhua', 'novel', 'one-shot'])],
            'cover_image'  => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'genres'       => ['nullable', 'array'],
            'genres.*'     => ['exists:genres,id'],
            'tags'         => ['nullable', 'array'],
            'tags.*'       => ['exists:tags,id'],
        ];
    }
}
