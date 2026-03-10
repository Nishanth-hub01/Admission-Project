<?php
// admission-system/dashboard/support_new_register.php
// Register New Student Component
?>

<form method="POST" id="registrationForm">
    
    <!-- SECTION 1: PERSONAL DETAILS -->
    <div class="section-card">
        <h3 class="section-title"><i class="fas fa-user"></i> 1. Personal Details</h3>
        <div class="row">
            <div class="col-md-6">
                <div class="field-group">
                    <label class="field-label">Full Name <span style="color: #e74c3c;">*</span></label>
                    <input type="text" name="full_name" class="form-control" placeholder="Enter full name" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="field-group">
                    <label class="field-label">Date of Birth <span style="color: #e74c3c;">*</span></label>
                    <input type="date" name="date_of_birth" class="form-control" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="field-group">
                    <label class="field-label">Gender <span style="color: #e74c3c;">*</span></label>
                    <select name="gender" class="form-select" required>
                        <option value="">Select Gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="field-group">
                    <label class="field-label">Nationality</label>
                    <input type="text" name="nationality" class="form-control" value="Indian" placeholder="Nationality">
                </div>
            </div>
            <div class="col-md-6">
                <div class="field-group">
                    <label class="field-label">Religion</label>
                    <input type="text" name="religion" class="form-control" placeholder="Enter religion">
                </div>
            </div>
            <div class="col-md-6">
                <div class="field-group">
                    <label class="field-label">Community/Category <span style="color: #e74c3c;">*</span></label>
                    <select name="community" class="form-select" required>
                        <option value="">Select Category</option>
                        <option value="General">General</option>
                        <option value="OBC">OBC</option>
                        <option value="SC">SC</option>
                        <option value="ST">ST</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="field-group">
                    <label class="field-label">Aadhaar Number</label>
                    <input type="text" name="aadhaar_number" class="form-control" placeholder="12-digit Aadhaar" pattern="[0-9]{12}">
                </div>
            </div>
            <div class="col-md-6">
                <div class="field-group">
                    <label class="field-label">Blood Group</label>
                    <select name="blood_group" class="form-select">
                        <option value="">Select Blood Group</option>
                        <option value="O+">O+</option>
                        <option value="O-">O-</option>
                        <option value="A+">A+</option>
                        <option value="A-">A-</option>
                        <option value="B+">B+</option>
                        <option value="B-">B-</option>
                        <option value="AB+">AB+</option>
                        <option value="AB-">AB-</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- SECTION 2: CONTACT DETAILS -->
    <div class="section-card">
        <h3 class="section-title"><i class="fas fa-phone"></i> 2. Contact Details</h3>
        <div class="row">
            <div class="col-md-6">
                <div class="field-group">
                    <label class="field-label">Mobile Number <span style="color: #e74c3c;">*</span></label>
                    <input type="tel" name="mobile_number" class="form-control" placeholder="10-digit mobile" pattern="[0-9]{10}" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="field-group">
                    <label class="field-label">Alternate Mobile Number</label>
                    <input type="tel" name="alternate_mobile" class="form-control" placeholder="Alternate mobile" pattern="[0-9]{10}">
                </div>
            </div>
            <div class="col-md-6">
                <div class="field-group">
                    <label class="field-label">Email ID <span style="color: #e74c3c;">*</span></label>
                    <input type="email" name="email_id" class="form-control" placeholder="your.email@example.com" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="field-group">
                    <label class="field-label">City <span style="color: #e74c3c;">*</span></label>
                    <input type="text" name="city" class="form-control" placeholder="Enter city name" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="field-group">
                    <label class="field-label">State <span style="color: #e74c3c;">*</span></label>
                    <input type="text" name="state" class="form-control" placeholder="Enter state name" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="field-group">
                    <label class="field-label">Pincode <span style="color: #e74c3c;">*</span></label>
                    <input type="text" name="pincode" class="form-control" placeholder="6-digit pincode" pattern="[0-9]{6}" required>
                </div>
            </div>
            <div class="col-md-12">
                <div class="field-group">
                    <label class="field-label">Permanent Address <span style="color: #e74c3c;">*</span></label>
                    <textarea name="permanent_address" class="form-control" rows="3" placeholder="Enter complete permanent address" required></textarea>
                </div>
            </div>
            <div class="col-md-12">
                <div class="field-group">
                    <label class="field-label">Current Address</label>
                    <textarea name="current_address" class="form-control" rows="3" placeholder="Enter complete current address"></textarea>
                </div>
            </div>
        </div>
    </div>

    <!-- SECTION 3: PARENT/GUARDIAN DETAILS -->
    <div class="section-card">
        <h3 class="section-title"><i class="fas fa-users"></i> 3. Parent / Guardian Details</h3>
        <div class="row">
            <div class="col-md-6">
                <div class="field-group">
                    <label class="field-label">Father's Name</label>
                    <input type="text" name="father_name" class="form-control" placeholder="Father's full name">
                </div>
            </div>
            <div class="col-md-6">
                <div class="field-group">
                    <label class="field-label">Mother's Name</label>
                    <input type="text" name="mother_name" class="form-control" placeholder="Mother's full name">
                </div>
            </div>
            <div class="col-md-6">
                <div class="field-group">
                    <label class="field-label">Guardian Name</label>
                    <input type="text" name="guardian_name" class="form-control" placeholder="Guardian's full name">
                </div>
            </div>
            <div class="col-md-6">
                <div class="field-group">
                    <label class="field-label">Parent Occupation</label>
                    <input type="text" name="parent_occupation" class="form-control" placeholder="Parent's occupation">
                </div>
            </div>
            <div class="col-md-6">
                <div class="field-group">
                    <label class="field-label">Parent Mobile Number</label>
                    <input type="tel" name="parent_mobile" class="form-control" placeholder="Parent's mobile" pattern="[0-9]{10}">
                </div>
            </div>
            <div class="col-md-6">
                <div class="field-group">
                    <label class="field-label">Parent Email ID</label>
                    <input type="email" name="parent_email" class="form-control" placeholder="Parent's email">
                </div>
            </div>
            <div class="col-md-12">
                <div class="field-group">
                    <label class="field-label">Annual Family Income</label>
                    <input type="number" name="annual_family_income" class="form-control" placeholder="Annual income in rupees">
                </div>
            </div>
        </div>
    </div>

    <!-- SECTION 4: ACADEMIC DETAILS (10TH) -->
    <div class="section-card">
        <h3 class="section-title"><i class="fas fa-book"></i> 4. Academic Details - 10th Class</h3>
        <div class="row">
            <div class="col-md-6">
                <div class="field-group">
                    <label class="field-label">School Name</label>
                    <input type="text" name="class_10_school" class="form-control" placeholder="10th school name">
                </div>
            </div>
            <div class="col-md-6">
                <div class="field-group">
                    <label class="field-label">Board</label>
                    <select name="class_10_board" class="form-select">
                        <option value="State Board">State Board</option>
                        <option value="CBSE">CBSE</option>
                        <option value="ICSE">ICSE</option>
                        <option value="IB">IB</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="field-group">
                    <label class="field-label">Register Number</label>
                    <input type="text" name="class_10_register_number" class="form-control" placeholder="10th register number">
                </div>
            </div>
            <div class="col-md-6">
                <div class="field-group">
                    <label class="field-label">Percentage / CGPA</label>
                    <input type="number" step="0.01" name="class_10_percentage" class="form-control" placeholder="0.00">
                </div>
            </div>
        </div>
    </div>

    <!-- SECTION 5: ACADEMIC DETAILS (12TH) -->
    <div class="section-card">
        <h3 class="section-title"><i class="fas fa-book"></i> 5. Academic Details - 12th Class</h3>
        <div class="row">
            <div class="col-md-6">
                <div class="field-group">
                    <label class="field-label">School Name</label>
                    <input type="text" name="class_12_school" class="form-control" placeholder="12th school name">
                </div>
            </div>
            <div class="col-md-6">
                <div class="field-group">
                    <label class="field-label">Board</label>
                    <select name="class_12_board" class="form-select">
                        <option value="State Board">State Board</option>
                        <option value="CBSE">CBSE</option>
                        <option value="ICSE">ICSE</option>
                        <option value="IB">IB</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="field-group">
                    <label class="field-label">Register Number</label>
                    <input type="text" name="class_12_register_number" class="form-control" placeholder="12th register number">
                </div>
            </div>
            <div class="col-md-6">
                <div class="field-group">
                    <label class="field-label">Percentage</label>
                    <input type="number" step="0.01" name="class_12_percentage" class="form-control" placeholder="0.00">
                </div>
            </div>
            <div class="col-md-12">
                <div class="field-group">
                    <label class="field-label">Subjects Studied</label>
                    <input type="text" name="class_12_subjects" class="form-control" placeholder="e.g., Physics, Chemistry, Mathematics">
                </div>
            </div>
        </div>
    </div>

    <!-- SECTION 6: ENTRANCE EXAM -->
    <div class="section-card">
        <h3 class="section-title"><i class="fas fa-certificate"></i> 6. Entrance Exam Details</h3>
        <div class="row">
            <div class="col-md-6">
                <div class="field-group">
                    <label class="field-label">Entrance Exam Type</label>
                    <select name="entrance_exam_type" class="form-select">
                        <option value="">Select Exam</option>
                        <option value="JEE Main">JEE Main</option>
                        <option value="NEET">NEET</option>
                        <option value="CUET">CUET</option>
                        <option value="State CET">State CET</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="field-group">
                    <label class="field-label">Entrance Exam Score</label>
                    <input type="number" step="0.01" name="entrance_exam_score" class="form-control" placeholder="Score obtained">
                </div>
            </div>
        </div>
    </div>

    <!-- SECTION 7: COURSE SELECTION -->
    <div class="section-card">
        <h3 class="section-title"><i class="fas fa-graduation-cap"></i> 7. Course Selection</h3>
        <div class="row">
            <div class="col-md-6">
                <div class="field-group">
                    <label class="field-label">Degree Type</label>
                    <select name="degree_type" class="form-select">
                        <option value="B.Tech">B.Tech</option>
                        <option value="B.Sc">B.Sc</option>
                        <option value="B.Com">B.Com</option>
                        <option value="B.A">B.A</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="field-group">
                    <label class="field-label">Course / Department</label>
                    <input type="text" name="course_department" class="form-control" placeholder="e.g., Computer Science">
                </div>
            </div>
            <div class="col-md-6">
                <div class="field-group">
                    <label class="field-label">Preferred Specialization</label>
                    <input type="text" name="preferred_specialization" class="form-control" placeholder="e.g., Artificial Intelligence">
                </div>
            </div>
            <div class="col-md-6">
                <div class="field-group">
                    <label class="field-label">Admission Type</label>
                    <select name="admission_type" class="form-select">
                        <option value="Merit">Merit</option>
                        <option value="Management">Management</option>
                        <option value="Entrance">Entrance</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- SECTION 8: BANK DETAILS -->
    <div class="section-card">
        <h3 class="section-title"><i class="fas fa-bank"></i> 8. Bank Details</h3>
        <div class="row">
            <div class="col-md-6">
                <div class="field-group">
                    <label class="field-label">Account Holder Name</label>
                    <input type="text" name="account_holder_name" class="form-control" placeholder="Account holder name">
                </div>
            </div>
            <div class="col-md-6">
                <div class="field-group">
                    <label class="field-label">Bank Name</label>
                    <input type="text" name="bank_name" class="form-control" placeholder="Bank name">
                </div>
            </div>
            <div class="col-md-6">
                <div class="field-group">
                    <label class="field-label">Account Number</label>
                    <input type="text" name="account_number" class="form-control" placeholder="Account number">
                </div>
            </div>
            <div class="col-md-6">
                <div class="field-group">
                    <label class="field-label">IFSC Code</label>
                    <input type="text" name="ifsc_code" class="form-control" placeholder="IFSC code">
                </div>
            </div>
            <div class="col-md-12">
                <div class="field-group">
                    <label class="field-label">Branch Name</label>
                    <input type="text" name="branch_name" class="form-control" placeholder="Branch name">
                </div>
            </div>
        </div>
    </div>

    <!-- SECTION 9: ADDITIONAL INFORMATION -->
    <div class="section-card">
        <h3 class="section-title"><i class="fas fa-info-circle"></i> 9. Additional Information</h3>
        <div class="row">
            <div class="col-md-6">
                <div class="field-group">
                    <label class="field-label">Hostel Requirement</label>
                    <select name="hostel_requirement" class="form-select">
                        <option value="No">No</option>
                        <option value="Yes">Yes</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="field-group">
                    <label class="field-label">Transport Requirement</label>
                    <select name="transport_requirement" class="form-select">
                        <option value="No">No</option>
                        <option value="Yes">Yes</option>
                    </select>
                </div>
            </div>
            <div class="col-md-12">
                <div class="field-group">
                    <label class="field-label">Scholarship Details</label>
                    <textarea name="scholarship_details" class="form-control" rows="2" placeholder="If any scholarship details"></textarea>
                </div>
            </div>
            <div class="col-md-12">
                <div class="field-group">
                    <label class="field-label">Sports / Extracurricular Achievements</label>
                    <textarea name="sports_achievements" class="form-control" rows="2" placeholder="List achievements if any"></textarea>
                </div>
            </div>
            <div class="col-md-12">
                <div class="field-group">
                    <label class="field-label">Medical Information</label>
                    <textarea name="medical_information" class="form-control" rows="2" placeholder="Any medical conditions or allergies"></textarea>
                </div>
            </div>
        </div>
    </div>

    <!-- SUBMIT BUTTON -->
    <div class="section-card" style="text-align: center; background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%); border-left-color: #2ecc71;">
        <button type="submit" name="register_student" class="btn btn-primary" style="padding: 15px 40px; font-size: 1.1rem;">
            <i class="fas fa-check-circle"></i> Register Student & Generate Admission ID
        </button>
        <p style="color: #999; margin-top: 15px; font-size: 0.9rem;">
            <i class="fas fa-info-circle"></i> All fields marked with <span style="color: #e74c3c;">*</span> are mandatory
        </p>
    </div>
</form>

<!-- FORM VALIDATION SCRIPT -->
<script>
    document.getElementById('registrationForm').addEventListener('submit', function(e) {
        const fullName = document.querySelector('input[name="full_name"]').value.trim();
        const email = document.querySelector('input[name="email_id"]').value.trim();
        const mobile = document.querySelector('input[name="mobile_number"]').value.trim();
        const dob = document.querySelector('input[name="date_of_birth"]').value;
        
        if (!fullName || !email || !mobile || !dob) {
            e.preventDefault();
            alert('Please fill all required fields');
            return false;
        }
        
        // Email validation
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            e.preventDefault();
            alert('Please enter a valid email address');
            return false;
        }
        
        // Mobile validation
        if (!/^\d{10}$/.test(mobile)) {
            e.preventDefault();
            alert('Please enter a valid 10-digit mobile number');
            return false;
        }
    });
</script>