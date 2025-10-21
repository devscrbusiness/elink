<?php

namespace App\Livewire\Business;

use App\Models\Business;
use App\Models\Document;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class EditBusinessDocuments extends Component
{
    use WithFileUploads;

    public Business $business;
    public $documents;
    public bool $isAdminEditing = false;

    public $documentFile;
    public string $documentName = '';

    public ?int $editingDocumentId = null;
    public string $editingDocumentName = '';

    public function mount(Business $business)
    {
        $user = Auth::user();
        if ($user->role !== 1 && $business->user_id !== $user->id) {
            abort(403, 'No tienes permiso para editar esta empresa.');
        }

        $this->isAdminEditing = ($user->role === 1 && $business->user_id !== $user->id);
        $this->business = $business;
        $this->loadDocuments();
    }

    public function loadDocuments()
    {
        $this->documents = $this->business->documents()->orderBy('created_at', 'desc')->get();
    }

    protected function rules(): array
    {
        return [
            'documentName' => 'required|string|max:255',
            'documentFile' => 'required|file|mimes:pdf|max:10240', // 10MB Max
        ];
    }

    public function saveDocument()
    {
        $this->validate();

        $path = $this->documentFile->store('documents', 'public');

        $this->business->documents()->create([
            'name' => $this->documentName,
            'path' => $path,
        ]);

        session()->flash('message', __('edit-business.document_upload_success'));
        $this->reset(['documentName', 'documentFile']);
        $this->loadDocuments();
    }

    public function editDocument(Document $document)
    {
        $this->editingDocumentId = $document->id;
        $this->editingDocumentName = $document->name;
        $this->resetErrorBag();
    }

    public function cancelEdit()
    {
        $this->reset('editingDocumentId', 'editingDocumentName');
    }

    public function updateDocument()
    {
        $this->validate([
            'editingDocumentName' => 'required|string|max:255',
        ], [], [
            'editingDocumentName' => __('edit-business.document_name_label'),
        ]);

        $document = Document::find($this->editingDocumentId);
        if ($document && $document->business_id === $this->business->id) {
            $document->update(['name' => $this->editingDocumentName]);
            session()->flash('message', __('edit-business.document_update_success'));
            $this->loadDocuments();
        }

        $this->cancelEdit();
    }

    public function deleteDocument(Document $document)
    {
        Storage::disk('public')->delete($document->path);
        $document->delete();

        session()->flash('message', __('edit-business.document_delete_success'));
        $this->loadDocuments();
    }

    public function render()
    {
        return view('livewire.edit-business-documents', [
            'user' => $this->business->user,
        ]);
    }
}