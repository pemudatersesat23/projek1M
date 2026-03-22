<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index()
    {
        $faqs = Faq::orderBy('order')->get();
        return view('admin.faqs.index', compact('faqs'));
    }

    public function create()
    {
        return view('admin.faqs.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kategori_id' => 'required|string',
            'question_id' => 'required|string',
            'answer_id' => 'required|string',
            'order' => 'required|integer'
        ]);

        $faq = new Faq();
        $faq->setTranslation('kategori', 'id', $validated['kategori_id']);
        $faq->setTranslation('question', 'id', $validated['question_id']);
        $faq->setTranslation('answer', 'id', $validated['answer_id']);
        $faq->order = $validated['order'];
        $faq->is_active = $request->has('is_active');
        $faq->save();

        return redirect()->route('admin.faqs.index')->with('success', 'FAQ berhasil ditambahkan.');
    }

    public function edit(Faq $faq)
    {
        return view('admin.faqs.form', compact('faq'));
    }

    public function update(Request $request, Faq $faq)
    {
        $validated = $request->validate([
            'kategori_id' => 'required|string',
            'question_id' => 'required|string',
            'answer_id' => 'required|string',
            'order' => 'required|integer'
        ]);

        $faq->setTranslation('kategori', 'id', $validated['kategori_id']);
        $faq->setTranslation('question', 'id', $validated['question_id']);
        $faq->setTranslation('answer', 'id', $validated['answer_id']);
        $faq->order = $validated['order'];
        $faq->is_active = $request->has('is_active');
        $faq->save();

        return redirect()->route('admin.faqs.index')->with('success', 'FAQ berhasil diperbarui.');
    }

    public function destroy(Faq $faq)
    {
        $faq->delete();
        return redirect()->route('admin.faqs.index')->with('success', 'FAQ berhasil dihapus.');
    }
}
