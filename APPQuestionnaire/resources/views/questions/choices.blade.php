{{--@if(!empty($choices))--}}
{{--    @foreach($choices as $index => $choice)--}}
{{--        <div class="col-md-6">--}}
{{--            <div class="card shadow-sm">--}}
{{--                <div class="card-body">--}}
{{--                    <input type="hidden" name="{{ $type }}[{{ $index }}][id]" value="{{ $choice->id ?? '' }}">--}}
{{--                    <div class="mb-3">--}}
{{--                        <label for="{{ $type }}[{{ $index }}][label]" class="form-label">Libellé du choix :</label>--}}
{{--                        <input type="text" name="{{ $type }}[{{ $index }}][label]" class="form-control" value="{{ old($type.'.'.$index.'.label', $choice->label) }}" required>--}}
{{--                    </div>--}}
{{--                    @if($type == 'multiple')--}}
{{--                        <div class="mb-3">--}}
{{--                            <label for="{{ $type }}[{{ $index }}][nombre_de]" class="form-label">De :</label>--}}
{{--                            <input type="number" name="{{ $type }}[{{ $index }}][nombre_de]" class="form-control" value="{{ old($type.'.'.$index.'.de', $choice->de) }}" required>--}}
{{--                        </div>--}}
{{--                        <div class="mb-3">--}}
{{--                            <label for="{{ $type }}[{{ $index }}][a]" class="form-label">À :</label>--}}
{{--                            <input type="number" name="{{ $type }}[{{ $index }}][a]" class="form-control" value="{{ old($type.'.'.$index.'.a', $choice->a) }}" required>--}}
{{--                        </div>--}}
{{--                    @else--}}
{{--                        <div class="mb-3">--}}
{{--                            <label for="{{ $type }}[{{ $index }}][question]" class="form-label">Question associée (facultatif) :</label>--}}
{{--                            <input type="text" name="{{ $type }}[{{ $index }}][question]" class="form-control" value="{{ old($type.'.'.$index.'.question', $choice->question) }}">--}}
{{--                        </div>--}}
{{--                    @endif--}}
{{--                    <button type="button" class="btn btn-danger btn-sm" onclick="removeChoice(this, '{{ $type }}')">Supprimer</button>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    @endforeach--}}
{{--@endif--}}
