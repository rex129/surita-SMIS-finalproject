<label for="student_id">Student</label>
<select id="student_id" name="student_id" class="{{ $errors->has('student_id') ? 'invalid' : '' }}" required>
    <option value="">-- Select student --</option>
    @foreach ($students as $s)<option value="{{ $s->id }}" @selected(old('student_id', $enrollment->student_id) == $s->id)>{{ $s->student_number }} - {{ $s->full_name }}</option>@endforeach
</select>
@error('student_id')<div class="error">{{ $message }}</div>@enderror
<label for="subject_id">Subject</label>
<select id="subject_id" name="subject_id" class="{{ $errors->has('subject_id') ? 'invalid' : '' }}" required>
    <option value="">-- Select subject --</option>
    @foreach ($subjects as $s)<option value="{{ $s->id }}" @selected(old('subject_id', $enrollment->subject_id) == $s->id)>{{ $s->code }} - {{ $s->name }}</option>@endforeach
</select>
@error('subject_id')<div class="error">{{ $message }}</div>@enderror
<label for="school_year">School Year (e.g. 2025-2026)</label>
<input id="school_year" name="school_year" value="{{ old('school_year', $enrollment->school_year ?? '2025-2026') }}" class="{{ $errors->has('school_year') ? 'invalid' : '' }}" required>
@error('school_year')<div class="error">{{ $message }}</div>@enderror
<label for="semester">Semester</label>
<select id="semester" name="semester" class="{{ $errors->has('semester') ? 'invalid' : '' }}" required>
    @foreach (\App\Models\Enrollment::SEMESTERS as $sem)<option value="{{ $sem }}" @selected(old('semester', $enrollment->semester) === $sem)>{{ $sem }}</option>@endforeach
</select>
@error('semester')<div class="error">{{ $message }}</div>@enderror
<label for="status">Status</label>
<select id="status" name="status" class="{{ $errors->has('status') ? 'invalid' : '' }}" required>
    @foreach (\App\Models\Enrollment::STATUSES as $st)<option value="{{ $st }}" @selected(old('status', $enrollment->status) === $st)>{{ ucfirst($st) }}</option>@endforeach
</select>
@error('status')<div class="error">{{ $message }}</div>@enderror
<label for="grade">Grade (1.00 - 5.00, optional)</label>
<input id="grade" name="grade" type="number" step="0.01" min="1" max="5" value="{{ old('grade', $enrollment->grade) }}" class="{{ $errors->has('grade') ? 'invalid' : '' }}">
@error('grade')<div class="error">{{ $message }}</div>@enderror
<p><button class="btn">Save</button> <a class="btn secondary" href="{{ route('enrollments.index') }}">Cancel</a></p>
