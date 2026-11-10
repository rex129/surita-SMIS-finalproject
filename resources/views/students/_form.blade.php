@php $fields = ['student_number' => 'Student Number', 'first_name' => 'First Name', 'last_name' => 'Last Name', 'email' => 'Email', 'course' => 'Course']; @endphp
@foreach ($fields as $name => $label)
    <label for="{{ $name }}">{{ $label }}</label>
    <input id="{{ $name }}" name="{{ $name }}" type="{{ $name === 'email' ? 'email' : 'text' }}" value="{{ old($name, $student->$name ?? '') }}" class="{{ $errors->has($name) ? 'invalid' : '' }}" required>
    @error($name)<div class="error">{{ $message }}</div>@enderror
@endforeach
<label for="year_level">Year Level</label>
<select id="year_level" name="year_level" class="{{ $errors->has('year_level') ? 'invalid' : '' }}" required>
    @foreach (range(1, 6) as $y)<option value="{{ $y }}" @selected(old('year_level', $student->year_level ?? 1) == $y)>Year {{ $y }}</option>@endforeach
</select>
@error('year_level')<div class="error">{{ $message }}</div>@enderror
<p><button class="btn">Save</button> <a class="btn secondary" href="{{ route('students.index') }}">Cancel</a></p>
