<?php

namespace App\Http\Requests\Manga;

use App\Models\Role;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->user();
 
        return $user->hasRole(Role::SUPER_ADMIN)
            || $user->hasRole(Role::ADMIN)
            || $user->hasRole(Role::UPLOADER);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title'        => ['required', 'string', 'max:255'],
            'description'  => ['nullable', 'string'],
            'author'       => ['nullable', 'string', 'max:255'],
            'artist'       => ['nullable', 'string', 'max:255'],
            'release_year' => ['nullable', 'integer', 'min:1900', 'max:' . date('Y')],
            'status'       => ['required', 'string', Rule::in(['ongoing', 'completed', 'hiatus', 'cancelled'])],
            'type'         => ['required', 'string', Rule::in(['manga', 'manhwa', 'manhua', 'novel', 'one-shot'])],
            'cover_image'  => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'genres'       => ['nullable', 'array'],
            'genres.*'     => ['exists:genres,id'],
            'tags'         => ['nullable', 'array'],
            'tags.*'       => ['exists:tags,id'],
        ];
    }
}
