<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ListExercisesRequest extends FormRequest
{
    public const DEFAULT_PER_PAGE = 20;

    public const MAX_PER_PAGE = 100;

    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, list<mixed>>
     */
    public function rules(): array
    {
        return [
            'search' => ['sometimes', 'string', 'max:255'],
            'category' => ['sometimes', 'string', Rule::exists('categories', 'slug')],
            'muscle' => ['sometimes', 'string', Rule::exists('muscles', 'slug')],
            'equipment' => ['sometimes', 'string', Rule::exists('equipment', 'slug')],
            'difficulty' => ['sometimes', 'string', Rule::in(['beginner', 'intermediate', 'expert'])],
            'force' => ['sometimes', 'string', Rule::in(['push', 'pull', 'static'])],
            'mechanic' => ['sometimes', 'string', Rule::in(['compound', 'isolation'])],
            'status' => ['sometimes', 'string', Rule::in(['published'])],
            'page' => ['sometimes', 'integer', 'min:1'],
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:'.self::MAX_PER_PAGE],
            'sort' => ['sometimes', 'string', Rule::in([
                'name',
                '-name',
                'created_at',
                '-created_at',
                'difficulty',
                '-difficulty',
            ])],
        ];
    }

    public function perPage(): int
    {
        return (int) $this->integer('per_page', self::DEFAULT_PER_PAGE);
    }
}
