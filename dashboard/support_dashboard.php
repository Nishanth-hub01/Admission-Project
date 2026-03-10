<?php
// Dashboard Home
?>
<div class="section-card">
    <h3 class="section-title">
        <i class="fas fa-chart-bar"></i> Welcome to Support Staff Dashboard
    </h3>
    <p style="color: #666; line-height: 1.8; margin: 15px 0;">
        As a support staff member, you have comprehensive access to student management tools. Use the options below to efficiently manage student registrations and queries.
    </p>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="section-card">
            <h3 class="section-title"><i class="fas fa-lightning-bolt"></i> Quick Actions</h3>
            <a href="?tab=search" class="btn btn-primary w-100 mb-2">
                <i class="fas fa-id-card"></i> Search by Admission ID
            </a>
            <a href="?tab=register" class="btn btn-primary w-100 mb-2">
                <i class="fas fa-user-plus"></i> Register New Student
            </a>
            <a href="?tab=all_students" class="btn btn-primary w-100">
                <i class="fas fa-list"></i> View All Students
            </a>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="section-card">
            <h3 class="section-title"><i class="fas fa-info-circle"></i> Key Information</h3>
            <div style="background: #f0f7ff; padding: 15px; border-radius: 8px; margin: 10px 0;">
                <strong style="color: #667eea;">Admission ID Format:</strong>
                <p style="margin: 8px 0; color: #666;">SI + Date + Month + Serial</p>
                <p style="margin: 0; color: #999; font-size: 0.9rem;">Example: SI1003001</p>
            </div>
            <div style="background: #fff3cd; padding: 15px; border-radius: 8px; margin: 10px 0; border-left: 4px solid #ffc107;">
                <strong style="color: #856404;">Features Available:</strong>
                <ul style="margin: 10px 0 0 0; padding-left: 20px; color: #666;">
                    <li>Search students by Admission ID</li>
                    <li>Register new students</li>
                    <li>View complete student details</li>
                    <li>Print student records</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- RECENT REGISTRATIONS -->
<div class="section-card">
    <h3 class="section-title"><i class="fas fa-history"></i> Recent Registrations</h3>
    <?php
    $recent = $conn->query("SELECT id, admission_id, full_name, email_id, created_at FROM students ORDER BY created_at DESC LIMIT 5");
    if ($recent && $recent->num_rows > 0):
    ?>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Admission ID</th>
                        <th>Student Name</th>
                        <th>Email</th>
                        <th>Registered</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $recent->fetch_assoc()): ?>
                        <tr>
                            <td><code style="background: #f0f0f0; padding: 4px 8px; border-radius: 4px;"><?php echo $row['admission_id']; ?></code></td>
                            <td><strong><?php echo htmlspecialchars($row['full_name']); ?></strong></td>
                            <td><?php echo htmlspecialchars($row['email_id']); ?></td>
                            <td><?php echo date('d M Y', strtotime($row['created_at'])); ?></td>
                            <td>
                                <a href="?tab=view_student&view_student=<?php echo $row['id']; ?>" class="btn btn-sm btn-primary">
                                    <i class="fas fa-eye"></i> View
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p style="text-align: center; color: #999; padding: 20px;">No students registered yet</p>
    <?php endif; ?>
</div>