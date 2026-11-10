<label for="code">Subject Code</label>
<input id="code" name="code" value="{{ old('code', $subject->code ?? '') }}" class="{{ $errors->has('code') ? 'invalid' : '' }}" required>
@error('code')<div class="error">{{ $message }}</div>@enderror
<label for="name">Subject Name</label>
<input id="name" name="name" value="{{ old('name', $subject->name ?? '') }}" class="{{ $errors->has('name') ? 'invalid' : '' }}" required>
@error('name')<div class="error">{{ $message }}</div>@enderror
<label for="units">Units</label>
<input id="units" name="units" type="number" min="1" max="6" value="{{ old('units', $subject->units ?? 3) }}" class="{{ $errors->has('units') ? 'invalid' : '' }}" required>
@error('units')<div class="error">{{ $message }}</div>@enderror
<p><button class="btn">Save</button> <a class="btn secondary" href="{{ route('subjects.index') }}">Cancel</a></p>
