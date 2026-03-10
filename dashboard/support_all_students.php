<?php
// admission-system/dashboard/support_new_all_students.php
// All Students List with Pagination
?>

<div class="section-card">
    <h3 class="section-title"><i class="fas fa-list"></i> All Registered Students</h3>
    
    <?php if (!empty($allStudents)): ?>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Admission ID</th>
                        <th>Student Name</th>
                        <th>Mobile Number</th>
                        <th>Email</th>
                        <th>Department</th>
                        <th>12th %</th>
                        <th>Status</th>
                        <th>Registered</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($allStudents as $student): ?>
                        <tr>
                            <td>
                                <code style="background: #f0f0f0; padding: 4px 8px; border-radius: 4px; font-size: 0.85rem;">
                                    <?php echo htmlspecialchars($student['admission_id']); ?>
                                </code>
                            </td>
                            <td>
                                <strong><?php echo htmlspecialchars($student['full_name']); ?></strong>
                            </td>
                            <td><?php echo htmlspecialchars($student['mobile_number']); ?></td>
                            <td><?php echo htmlspecialchars($student['email_id']); ?></td>
                            <td><?php echo htmlspecialchars($student['course_department'] ?? 'N/A'); ?></td>
                            <td><?php echo !empty($student['class_12_percentage']) ? $student['class_12_percentage'] . '%' : 'N/A'; ?></td>
                            <td>
                                <?php 
                                $statusColor = 'secondary';
                                if ($student['application_status'] == 'Approved') {
                                    $statusColor = 'success';
                                } elseif ($student['application_status'] == 'Rejected') {
                                    $statusColor = 'danger';
                                } elseif ($student['application_status'] == 'Submitted') {
                                    $statusColor = 'info';
                                }
                                ?>
                                <span class="badge bg-<?php echo $statusColor; ?>">
                                    <?php echo htmlspecialchars($student['application_status']); ?>
                                </span>
                            </td>
                            <td>
                                <small><?php echo date('d M Y', strtotime($student['created_at'])); ?></small>
                            </td>
                            <td>
                                <a href="?tab=view_student&view_student=<?php echo $student['id']; ?>" class="btn btn-sm btn-primary">
                                    <i class="fas fa-eye"></i> View
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- PAGINATION -->
        <?php if ($totalPages > 1): ?>
            <nav style="margin-top: 25px;">
                <ul class="pagination justify-content-center">
                    <?php if ($page > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?tab=all_students&page=1">
                                <i class="fas fa-chevron-left"></i> First
                            </a>
                        </li>
                        <li class="page-item">
                            <a class="page-link" href="?tab=all_students&page=<?php echo $page - 1; ?>">Previous</a>
                        </li>
                    <?php endif; ?>

                    <?php 
                    $start = max(1, $page - 2);
                    $end = min($totalPages, $page + 2);
                    for ($i = $start; $i <= $end; $i++): 
                    ?>
                        <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                            <a class="page-link" href="?tab=all_students&page=<?php echo $i; ?>">
                                <?php echo $i; ?>
                            </a>
                        </li>
                    <?php endfor; ?>

                    <?php if ($page < $totalPages): ?>
                        <li class="page-item">
                            <a class="page-link" href="?tab=all_students&page=<?php echo $page + 1; ?>">Next</a>
                        </li>
                        <li class="page-item">
                            <a class="page-link" href="?tab=all_students&page=<?php echo $totalPages; ?>">
                                Last <i class="fas fa-chevron-right"></i>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>

            <!-- PAGE INFO -->
            <div style="text-align: center; color: #666; margin-top: 15px;">
                <small>
                    Page <?php echo $page; ?> of <?php echo $totalPages; ?> 
                    | Total Records: <?php echo $totalRecords; ?>
                </small>
            </div>
        <?php endif; ?>

    <?php else: ?>
        <div style="text-align: center; padding: 40px;">
            <i class="fas fa-inbox" style="font-size: 3rem; color: #ddd; margin-bottom: 15px; display: block;"></i>
            <p style="color: #999; font-size: 1.1rem;">No students registered yet</p>
            <a href="?tab=register" class="btn btn-primary" style="margin-top: 15px;">
                <i class="fas fa-user-plus"></i> Register First Student
            </a>
        </div>
    <?php endif; ?>
</div>
