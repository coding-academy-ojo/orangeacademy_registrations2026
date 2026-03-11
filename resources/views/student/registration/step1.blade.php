@extends('layouts.student')
@section('title', 'Step 1 - Profile')
@section('content')
    @include('student.registration._progress', ['step' => 1])
    <div class="card">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold"><i class="bi bi-person-fill text-orange me-2"></i>Personal Information</h5>
        </div>
        <div class="card-body p-4">
            <form method="POST" action="{{ route('student.save.profile') }}">
                @csrf
                <div class="row g-3">
                    {{-- English Name 4 Parts --}}
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">First Name (English) <span
                                class="text-danger">*</span></label>
                        <input type="text" name="first_name_en" id="first_name_en" value="{{ old('first_name_en', $profile->first_name_en) }}"
                            class="form-control first_name_en" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Second Name (English)</label>
                        <input type="text" name="second_name_en" id="second_name_en"
                            value="{{ old('second_name_en', $profile->second_name_en) }}" class="form-control second_name_en">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Third Name (English)</label>
                        <input type="text" name="third_name_en" id="third_name_en" value="{{ old('third_name_en', $profile->third_name_en) }}"
                            class="form-control third_name_en">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Family Name (English) <span
                                class="text-danger">*</span></label>
                        <input type="text" name="last_name_en" id="last_name_en" value="{{ old('last_name_en', $profile->last_name_en) }}"
                            class="form-control last_name_en" required>
                    </div>

                    {{-- Arabic Name 4 Parts --}}
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">الاسم الأول (Arabic)</label>
                        <input type="text" name="first_name_ar" id="first_name_ar" value="{{ old('first_name_ar', $profile->first_name_ar) }}"
                            class="form-control first_name_ar" dir="rtl">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">اسم الأب (Arabic)</label>
                        <input type="text" name="second_name_ar" id="second_name_ar"
                            value="{{ old('second_name_ar', $profile->second_name_ar) }}" class="form-control second_name_ar" dir="rtl">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">اسم الجد (Arabic)</label>
                        <input type="text" name="third_name_ar" id="third_name_ar" value="{{ old('third_name_ar', $profile->third_name_ar) }}"
                            class="form-control third_name_ar" dir="rtl">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">اسم العائلة (Arabic)</label>
                        <input type="text" name="last_name_ar" id="last_name_ar" value="{{ old('last_name_ar', $profile->last_name_ar) }}"
                            class="form-control last_name_ar" dir="rtl">
                    </div>
                     <div class="col-md-6">
                        <label class="form-label fw-semibold">ID Number (National ID) <span class="text-danger">*</span></label>
                        <input type="text" name="id_number" id="id_number" value="{{ old('id_number', $profile->id_number) }}" class="form-control id_number"
                            placeholder="10 digits" pattern="\d{10}" maxlength="10"
                            oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10)" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Phone Number</label>
                        <input type="number" name="phone" id="phone" value="{{ old('phone', $profile->phone) }}" class="form-control phone"
                            placeholder="7xxxxxxxxx" min="100000000" max="99999999999999999999"
                            oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 20)">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Gender <span class="text-danger">*</span></label>
                        <select name="gender" id="gender" class="form-select gender" required>
                            <option value="">Select...</option>
                            @foreach(['male', 'female'] as $g)
                                <option value="{{ $g }}" {{ old('gender', $profile->gender) == $g ? 'selected' : '' }}>
                                    {{ ucfirst($g) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Date of Birth <span class="text-danger">*</span></label>
                        <input type="date" name="date_of_birth" id="date_of_birth"
                            value="{{ old('date_of_birth', $profile->date_of_birth?->format('Y-m-d')) }}"
                            class="form-control date_of_birth" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Nationality <span class="text-danger">*</span></label>
                        <select name="nationality" id="nationality" class="form-select border-0 bg-light py-2 nationality" required>
                            <option value="">Select Nationality...</option>
                            @php
                                $nationalities = ['Jordanian', 'Palestinian', 'Syrian', 'Egyptian', 'Iraqi', 'Lebanese', 'Saudi', 'Emirati', 'Other'];
                            @endphp
                            @foreach($nationalities as $n)
                                <option value="{{ $n }}" {{ old('nationality', $profile->nationality) == $n ? 'selected' : '' }}>
                                    {{ $n }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Country <span class="text-danger">*</span></label>
                        <select name="country" id="country" class="form-select border-0 bg-light py-2 country" required>
                            <option value="">Select Country...</option>
                            @php
                                $countries = ['Jordan', 'Palestine', 'Syria', 'Egypt', 'Iraq', 'Lebanon', 'Saudi Arabia', 'UAE', 'Other'];
                            @endphp
                            @foreach($countries as $c)
                                <option value="{{ $c }}" {{ old('country', $profile->country) == $c ? 'selected' : '' }}>{{ $c }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">City <span class="text-danger">*</span></label>
                        <select name="city" id="city_select" class="form-select border-0 bg-light py-2" required>
                            <option value="">Select City...</option>
                            @php
                                $cities = ['Amman', 'Zarqa', 'Irbid', 'Aqaba', 'Mafraq', 'Jerash', 'Madaba', 'Ajloun', 'Balqa', 'Karak', 'Tafilah', 'Ma\'an', 'Other'];
                            @endphp
                            @foreach($cities as $city)
                                <option value="{{ $city }}" {{ old('city', $profile->city) == $city ? 'selected' : '' }}>
                                    {{ $city }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6" id="neighborhood_wrapper" style="display: none;">
                        <label class="form-label fw-semibold">Neighborhood</label>
                        <select name="neighborhood" id="neighborhood_select" class="form-select border-0 bg-light py-2">
                            <option value="">Select Neighborhood...</option>
                            {{-- Populated by JS --}}
                        </select>
                        <input type="hidden" id="old_neighborhood" value="{{ old('neighborhood', $profile->neighborhood) }}">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Address</label>
                        <textarea name="address" id="address" class="form-control address"
                            rows="2">{{ old('address', $profile->address) }}</textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Education Level</label>
                        <select name="education_level" id="education_level" class="form-select education_level">
                            <option value="">Select...</option>
                            @foreach(['High School', 'Diploma', 'Bachelor', 'Master', 'PhD', 'Other'] as $e)
                                <option value="{{ $e }}" {{ old('education_level', $profile->education_level) == $e ? 'selected' : '' }}>{{ $e }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Field of Study</label>
                        <select name="field_of_study" id="field_of_study_select" class="form-select border-0 bg-light py-2">
                            <option value="">Select Field of Study...</option>
                            @php
                                $fields = ['Computer Science', 'Software Engineering', 'Information Technology', 'Computer Engineering', 'Business Administration', 'Marketing', 'Accounting', 'Engineering', 'Medicine', 'Law', 'Arts & Design', 'Education', 'Data Science', 'Cybersecurity', 'Other'];
                            @endphp
                            @foreach($fields as $f)
                                <option value="{{ $f }}" {{ old('field_of_study', $profile->field_of_study) == $f ? 'selected' : '' }}>{{ $f }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- <div class="col-md-6" id="major_wrapper" style="display: none;">
                        <label class="form-label fw-semibold">Major / Specialization</label>
                        <select name="major" id="major_select" class="form-select border-0 bg-light py-2">
                            <option value="">Select Major...</option>
                            {{-- Populated by JS --}}
                        </select>
                        <input type="hidden" id="old_major" value="{{ old('major', $profile->major) }}">
                    </div> -->

                    {{-- University Information --}}
                    <div class="col-md-12 mt-4">
                        <h6 class="fw-bold border-bottom pb-2"><i class="bi bi-mortarboard text-orange me-2"></i>University & Graduation Details</h6>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label fw-semibold">University (Jordanian Universities)</label>
                        <select name="university" id="university" class="form-select border-0 bg-light py-2 university">
                            <option value="">Select University...</option>
                            <optgroup label="🏛️ Public Universities">
                                <option value="University of Jordan (UJ)" {{ old('university', $profile->university) == 'University of Jordan (UJ)' ? 'selected' : '' }}>University of Jordan (UJ)</option>
                                <option value="Jordan University of Science and Technology (JUST)" {{ old('university', $profile->university) == 'Jordan University of Science and Technology (JUST)' ? 'selected' : '' }}>Jordan University of Science and Technology (JUST)</option>
                                <option value="Yarmouk University" {{ old('university', $profile->university) == 'Yarmouk University' ? 'selected' : '' }}>Yarmouk University</option>
                                <option value="Al-Balqa Applied University (BAU)" {{ old('university', $profile->university) == 'Al-Balqa Applied University (BAU)' ? 'selected' : '' }}>Al-Balqa Applied University (BAU)</option>
                                <option value="Hashemite University (HU)" {{ old('university', $profile->university) == 'Hashemite University (HU)' ? 'selected' : '' }}>Hashemite University (HU)</option>
                                <option value="Mutah University" {{ old('university', $profile->university) == 'Mutah University' ? 'selected' : '' }}>Mutah University</option>
                                <option value="Al al-Bayt University" {{ old('university', $profile->university) == 'Al al-Bayt University' ? 'selected' : '' }}>Al al-Bayt University</option>
                                <option value="Al-Hussein Bin Talal University (AHU)" {{ old('university', $profile->university) == 'Al-Hussein Bin Talal University (AHU)' ? 'selected' : '' }}>Al-Hussein Bin Talal University (AHU)</option>
                                <option value="Tafila Technical University (TTU)" {{ old('university', $profile->university) == 'Tafila Technical University (TTU)' ? 'selected' : '' }}>Tafila Technical University (TTU)</option>
                                <option value="Jerash University" {{ old('university', $profile->university) == 'Jerash University' ? 'selected' : '' }}>Jerash University</option>
                                <option value="The World Islamic Sciences and Education University (WISE)" {{ old('university', $profile->university) == 'The World Islamic Sciences and Education University (WISE)' ? 'selected' : '' }}>World Islamic Sciences and Education University (WISE)</option>
                            </optgroup>
                            <optgroup label="🎓 Private Universities">
                                <option value="German Jordanian University (GJU)" {{ old('university', $profile->university) == 'German Jordanian University (GJU)' ? 'selected' : '' }}>German Jordanian University (GJU)</option>
                                <option value="Princess Sumaya University for Technology (PSUT)" {{ old('university', $profile->university) == 'Princess Sumaya University for Technology (PSUT)' ? 'selected' : '' }}>Princess Sumaya University for Technology (PSUT)</option>
                                <option value="Al-Ahliyya Amman University (AAU)" {{ old('university', $profile->university) == 'Al-Ahliyya Amman University (AAU)' ? 'selected' : '' }}>Al-Ahliyya Amman University (AAU)</option>
                                <option value="Applied Science Private University (ASU)" {{ old('university', $profile->university) == 'Applied Science Private University (ASU)' ? 'selected' : '' }}>Applied Science Private University (ASU)</option>
                                <option value="Philadelphia University" {{ old('university', $profile->university) == 'Philadelphia University' ? 'selected' : '' }}>Philadelphia University</option>
                                <option value="Al-Zaytoonah University of Jordan (ZUJ)" {{ old('university', $profile->university) == 'Al-Zaytoonah University of Jordan (ZUJ)' ? 'selected' : '' }}>Al-Zaytoonah University of Jordan (ZUJ)</option>
                                <option value="Petra University" {{ old('university', $profile->university) == 'Petra University' ? 'selected' : '' }}>Petra University</option>
                                <option value="Zarqa University (ZU)" {{ old('university', $profile->university) == 'Zarqa University (ZU)' ? 'selected' : '' }}>Zarqa University (ZU)</option>
                                <option value="Middle East University (MEU)" {{ old('university', $profile->university) == 'Middle East University (MEU)' ? 'selected' : '' }}>Middle East University (MEU)</option>
                                <option value="Arab Open University - Jordan (AOU)" {{ old('university', $profile->university) == 'Arab Open University - Jordan (AOU)' ? 'selected' : '' }}>Arab Open University - Jordan (AOU)</option>
                                <option value="Isra University" {{ old('university', $profile->university) == 'Isra University' ? 'selected' : '' }}>Isra University</option>
                                <option value="Al-Isra University" {{ old('university', $profile->university) == 'Al-Isra University' ? 'selected' : '' }}>Al-Isra University</option>
                                <option value="Irbid National University (INU)" {{ old('university', $profile->university) == 'Irbid National University (INU)' ? 'selected' : '' }}>Irbid National University (INU)</option>
                                <option value="Amman Arab University (AAU)" {{ old('university', $profile->university) == 'Amman Arab University (AAU)' ? 'selected' : '' }}>Amman Arab University (AAU)</option>
                                <option value="Al-Madinah International University" {{ old('university', $profile->university) == 'Al-Madinah International University' ? 'selected' : '' }}>Al-Madinah International University</option>
                                <option value="Jordan Academy of Music" {{ old('university', $profile->university) == 'Jordan Academy of Music' ? 'selected' : '' }}>Jordan Academy of Music</option>
                                <option value="Amman University College for Financial & Administrative Sciences" {{ old('university', $profile->university) == 'Amman University College for Financial & Administrative Sciences' ? 'selected' : '' }}>Amman University College for Financial & Admin. Sciences</option>
                                <option value="Luminus Technical University College (LTUC)" {{ old('university', $profile->university) == 'Luminus Technical University College (LTUC)' ? 'selected' : '' }}>Luminus Technical University College (LTUC)</option>
                                <option value="Al-Quds College" {{ old('university', $profile->university) == 'Al-Quds College' ? 'selected' : '' }}>Al-Quds College</option>
                                <option value="University of Petra" {{ old('university', $profile->university) == 'University of Petra' ? 'selected' : '' }}>University of Petra</option>
                                <option value="New Vision University - Jordan" {{ old('university', $profile->university) == 'New Vision University - Jordan' ? 'selected' : '' }}>New Vision University - Jordan</option>
                            </optgroup>
                            <optgroup label="🌐 International & Other">
                                <option value="Other" {{ old('university', $profile->university) == 'Other' ? 'selected' : '' }}>Other</option>
                            </optgroup>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Graduated?</label>
                        <div class="d-flex gap-4 mt-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="is_graduated" id="grad_yes" value="1" {{ old('is_graduated', $profile->is_graduated) === true || old('is_graduated', $profile->is_graduated) === '1' ? 'checked' : '' }}>
                                <label class="form-check-label" for="grad_yes">Yes, I have graduated</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="is_graduated" id="grad_no" value="0" {{ old('is_graduated', $profile->is_graduated) === false || old('is_graduated', $profile->is_graduated) === '0' ? 'checked' : '' }}>
                                <label class="form-check-label" for="grad_no">No, still studying</label>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6" id="grad_year_yes_wrapper" style="display: none;">
                        <label class="form-label fw-semibold">year of graduated</label>
                        <input type="number" name="graduation_year" id="graduation_year" class="form-control" placeholder="e.g. 2023" value="{{ old('graduation_year', $profile->graduation_year) }}" min="1950" max="{{ date('Y') }}">
                    </div>

                    <div class="col-md-6" id="grad_year_no_wrapper" style="display: none;">
                        <label class="form-label fw-semibold">which year you well graduated</label>
                        <input type="number" name="expected_graduation_year" id="expected_graduation_year" class="form-control" placeholder="e.g. 2025" value="{{ old('expected_graduation_year', $profile->expected_graduation_year) }}" min="{{ date('Y') }}" max="{{ date('Y') + 10 }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">GPA</label>
                        <input type="text" name="gpa_value" id="gpa_value" value="{{ old('gpa_value', $profile->gpa_value) }}" class="form-control gpa_value" placeholder="e.g. 3.5, 85%, Excellent, A+">
                    </div>
                </div>
                <div class="d-flex justify-content-end mt-4 pt-3 border-top">
                    <button type="submit" class="btn btn-orange">Save & Continue <i
                            class="bi bi-arrow-right ms-2"></i></button>
                </div>
            </form>
        </div>
    </div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // --- Neighborhood Logic ---
    const citySelect = document.getElementById('city_select');
    const neighborhoodWrapper = document.getElementById('neighborhood_wrapper');
    const neighborhoodSelect = document.getElementById('neighborhood_select');
    const oldNeighborhood = document.getElementById('old_neighborhood') ? document.getElementById('old_neighborhood').value : '';

    let neighborhoodsData = {};

    function populateNeighborhoods() {
        if (!citySelect) return;
        const city = citySelect.value;
        neighborhoodSelect.innerHTML = '<option value="">Select Neighborhood...</option>';
        if (city && neighborhoodsData[city]) {
            neighborhoodWrapper.style.display = 'block';
            neighborhoodsData[city].forEach(area => {
                const opt = document.createElement('option');
                opt.value = area;
                opt.textContent = area;
                if (area === oldNeighborhood) opt.selected = true;
                neighborhoodSelect.appendChild(opt);
            });
            // Re-apply translation for dynamically added options if Arabic is active
            if (window.OA_Lang && window.OA_Lang.current === 'ar') {
                window.OA_Lang.apply('ar');
            }
        } else {
            neighborhoodWrapper.style.display = 'none';
        }
    }

    if (citySelect) {
        // Load neighborhoods from JSON file
        fetch('{{ asset('data/neighborhoods.json') }}')
            .then(res => res.json())
            .then(data => {
                neighborhoodsData = data;
                populateNeighborhoods(); // populate immediately on load (for pre-filled forms)
            })
            .catch(() => {
                console.warn('Could not load neighborhoods data.');
            });

        citySelect.addEventListener('change', populateNeighborhoods);
    }

    // --- Major Logic ---
    const fieldSelect = document.getElementById('field_of_study_select');
    const majorWrapper = document.getElementById('major_wrapper');
    const majorSelect = document.getElementById('major_select');
    const oldMajor = document.getElementById('old_major') ? document.getElementById('old_major').value : '';

    let majorsData = {};

    function populateMajors() {
        if (!fieldSelect || !majorSelect || !majorWrapper) return;
        const field = fieldSelect.value;
        majorSelect.innerHTML = '<option value="">Select Major...</option>';
        if (field && majorsData[field]) {
            majorWrapper.style.display = 'block';
            majorsData[field].forEach(m => {
                const opt = document.createElement('option');
                opt.value = m;
                opt.textContent = m;
                if (m === oldMajor) opt.selected = true;
                majorSelect.appendChild(opt);
            });
            // Re-apply translation for dynamically added options if Arabic is active
            if (window.OA_Lang && window.OA_Lang.current === 'ar') {
                window.OA_Lang.apply('ar');
            }
        } else {
            majorWrapper.style.display = 'none';
        }
    }

    if (fieldSelect) {
        // Load majors from JSON
        fetch('{{ asset('data/majors.json') }}')
            .then(res => res.json())
            .then(data => {
                majorsData = data;
                populateMajors();
            })
            .catch(() => {
                console.warn('Could not load majors data.');
            });

        fieldSelect.addEventListener('change', populateMajors);
    }


    // --- Graduation Logic ---
    const gradYes = document.getElementById('grad_yes');
    const gradNo = document.getElementById('grad_no');
    const gradYearYesWrapper = document.getElementById('grad_year_yes_wrapper');
    const gradYearNoWrapper = document.getElementById('grad_year_no_wrapper');
    const graduationYearInput = document.getElementById('graduation_year');
    const expectedYearInput = document.getElementById('expected_graduation_year');

    function toggleGraduation() {
        if (!gradYes || !gradNo) return;
        if (gradYes.checked) {
            gradYearYesWrapper.style.display = 'block';
            gradYearNoWrapper.style.display = 'none';
            if (expectedYearInput) expectedYearInput.value = '';
        } else if (gradNo.checked) {
            gradYearYesWrapper.style.display = 'none';
            gradYearNoWrapper.style.display = 'block';
            if (graduationYearInput) graduationYearInput.value = '';
        } else {
            gradYearYesWrapper.style.display = 'none';
            gradYearNoWrapper.style.display = 'none';
        }
    }

    if (gradYes) gradYes.addEventListener('change', toggleGraduation);
    if (gradNo) gradNo.addEventListener('change', toggleGraduation);
    toggleGraduation();

    // --- GPA Logic ---
    const gpaTypeSelect = document.getElementById('gpa_type_select');
    const gpaValueWrapper = document.getElementById('gpa_value_wrapper');
    const gpaValueInput = document.getElementById('gpa_value_input');

    function updateGPA() {
        if (!gpaTypeSelect) return;
        const type = gpaTypeSelect.value;
        if (type) {
            gpaValueWrapper.style.display = 'block';
            if (type === 'percentage') gpaValueInput.placeholder = 'e.g. 85.5 or 90';
            else if (type === 'gpa_4') gpaValueInput.placeholder = 'e.g. 3.5 or 2.8';
            else if (type === 'grade') gpaValueInput.placeholder = 'e.g. Excellent, Very Good, A, B+';
        } else {
            gpaValueWrapper.style.display = 'none';
        }
    }

    if (gpaTypeSelect) {
        gpaTypeSelect.addEventListener('change', updateGPA);
        updateGPA();
    }
});
</script>
@endsection