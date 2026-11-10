<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSubjectRequest;
use App\Http\Requests\UpdateSubjectRequest;
use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index(Request $request)
    {
        $term = $request->query('q');
        $subjects = Subject::withCount('enrollments')
            ->when($term, fn ($q) => $q->where(fn ($w) => $w->where('code', 'like', "%{$term}%")->orWhere('name', 'like', "%{$term}%")))
            ->orderBy('code')->paginate(7)->withQueryString();

        return view('subjects.index', compact('subjects'));
    }

    public function create()
    {
        return view('subjects.create');
    }

    public function store(StoreSubjectRequest $request)
    {
        $subject = Subject::create($request->validated());

        return redirect()->route('subjects.show', $subject)->with('success', 'Subject created.');
    }

    public function show(Subject $subject)
    {
        $subject->load('enrollments.student');

        return view('subjects.show', compact('subject'));
    }

    public function edit(Subject $subject)
    {
        return view('subjects.edit', compact('subject'));
    }

    public function update(UpdateSubjectRequest $request, Subject $subject)
    {
        $subject->update($request->validated());

        return redirect()->route('subjects.show', $subject)->with('success', 'Subject updated.');
    }

    public function destroy(Subject $subject)
    {
        $subject->delete();

        return redirect()->route('subjects.index')->with('success', 'Subject deleted.');
    }
}
