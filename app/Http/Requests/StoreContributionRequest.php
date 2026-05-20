<?php

namespace App\Http\Requests;

use App\Models\Setting;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreContributionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if (Setting::get('form_status', 'open') === 'closed') {
            abort(response()->json([
                'message' => 'Le formulaire est actuellement fermé.',
                'errors'  => ['form' => ['Le formulaire est actuellement fermé.']],
            ], 422));
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nom'               => 'required|string|max:255',
            'prenom'            => 'required|string|max:255',
            'email'             => 'required|email|max:255',
            'session'           => ['required', Rule::in([
                'Systèmes & sécurité alimentaire',
                'Technologies émergentes, données et santé',
                'Interfaces biologiques, émergences sanitaires et dynamiques de transmission',
                'Bien-être, durabilité et résilience des systèmes vivants',
            ])],
            'contribution_type' => 'required|in:Poster,Communication orale',
            'poster_file'       => 'required_if:contribution_type,Poster|nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'payment_status'    => 'required_if:contribution_type,Communication orale|nullable|in:Oui,Pas encore',
            'payment_receipt'   => 'required_if:payment_status,Oui|nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ];
    }

    public function messages(): array
    {
        return [
            'nom.required'               => 'Le nom est obligatoire.',
            'prenom.required'            => 'Le prénom est obligatoire.',
            'email.required'             => 'L\'adresse e-mail est obligatoire.',
            'email.email'                => 'Veuillez entrer une adresse e-mail valide.',
            'session.required'           => 'Veuillez sélectionner une session.',
            'session.in'                 => 'Veuillez choisir une session valide.',
            'contribution_type.required' => 'Veuillez sélectionner un type de contribution.',
            'poster_file.required_if'    => 'Le fichier poster est obligatoire pour ce type de contribution.',
            'poster_file.mimes'          => 'Le poster doit être un fichier jpg, jpeg, png ou pdf.',
            'poster_file.max'            => 'Le poster ne doit pas dépasser 5 Mo.',
            'payment_status.required_if' => 'Le statut de paiement est obligatoire.',
            'payment_receipt.required_if'=> 'Le reçu de paiement est obligatoire.',
            'payment_receipt.mimes'      => 'Le reçu doit être un fichier jpg, jpeg, png ou pdf.',
            'payment_receipt.max'        => 'Le reçu ne doit pas dépasser 5 Mo.',
        ];
    }
}
