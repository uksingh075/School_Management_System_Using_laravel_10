<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="" class="brand-link">
        <span class="brand-text font-weight-bold">
            <center>School Management<br>System</center>
        </span>
    </a>

    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img style="height: 40px; width: 40px;" src="{{ Auth::user()->getProfile() }}" class="img-circle elevation-2" alt="{{ Auth::user()->name }}">
            </div>
            <div class="info">
                <a href="" class="d-block">{{ Auth::user()->name }} {{ Auth::user()->last_name }}</a>
            </div>
        </div>

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">

                @if (Auth::user()->user_type == 1)
                    <li class="nav-item">
                        <a href="{{ url('admin/dashboard') }}"
                            class="nav-link @if (Request::segment(2) == 'dashboard') active @endif">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>
                                Dashboard
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ url('admin/admin/list') }}"
                            class="nav-link @if (Request::segment(2) == 'admin') active @endif">
                            <i class="nav-icon far fa-user"></i>
                            <p>
                                Admin
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ url('admin/teacher/list') }}"
                            class="nav-link @if (Request::segment(2) == 'teacher') active @endif">
                            <i class="nav-icon far fa-user"></i>
                            <p>
                                Teacher
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ url('admin/student/list') }}"
                            class="nav-link @if (Request::segment(2) == 'student') active @endif">
                            <i class="nav-icon far fa-user"></i>
                            <p>
                                Student
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ url('admin/parent/list') }}"
                            class="nav-link @if (Request::segment(2) == 'parent') active @endif">
                            <i class="nav-icon far fa-user"></i>
                            <p>
                                Parent
                            </p>
                        </a>
                    </li>


                    <li class="nav-item @if (Request::segment(2) == 'class' ||
                            Request::segment(2) == 'subject' ||
                            Request::segment(2) == 'assign_subject' ||
                            Request::segment(2) == 'assign_class_teacher' ||
                            Request::segment(2) == 'class_timetable') menu-is-opening menu-open @endif">
                        <a href="#" class="nav-link @if (Request::segment(2) == 'class' ||
                                Request::segment(2) == 'subject' ||
                                Request::segment(2) == 'assign_subject' ||
                                Request::segment(2) == 'assign_class_teacher' ||
                                Request::segment(2) == 'class_timetable') active @endif">
                            <i class="nav-icon fas fa-table"></i>
                            <p>
                                Academics
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ url('admin/class/list') }}"
                                    class="nav-link @if (Request::segment(2) == 'class') active @endif">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Class</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('admin/subject/list') }}"
                                    class="nav-link @if (Request::segment(2) == 'subject') active @endif">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Subject</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('admin/assign_subject/list') }}"
                                    class="nav-link @if (Request::segment(2) == 'assign_subject') active @endif">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Assign Subject</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('admin/class_timetable/list') }}"
                                    class="nav-link @if (Request::segment(2) == 'class_timetable') active @endif">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Class Timetable</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('admin/assign_class_teacher/list') }}"
                                    class="nav-link @if (Request::segment(2) == 'assign_class_teacher') active @endif">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Assign Class to Teacher</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item @if (Request::segment(2) == 'fees_collection') menu-is-opening menu-open @endif">
                        <a href="#" class="nav-link @if (Request::segment(2) == 'fees_collection') active @endif">
                            <i class="nav-icon fas fa-table"></i>
                            <p>
                                Fees Collection
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ url('admin/fees_collection/collect_fees') }}"
                                    class="nav-link @if (Request::segment(3) == 'collect_fees') active @endif">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Collect Fees</p>
                                </a>
                            </li>
                        </ul>

                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ url('admin/fees_collection/collect_fees_report') }}"
                                    class="nav-link @if (Request::segment(3) == 'collect_fees_report') active @endif">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Collect Fees Report</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item @if (Request::segment(2) == 'examinations') menu-is-opening menu-open @endif">
                        <a href="#" class="nav-link @if (Request::segment(2) == 'examinations') active @endif">
                            <i class="nav-icon fas fa-table"></i>
                            <p>
                                Examinations
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ url('admin/examinations/exam/list') }}"
                                    class="nav-link @if (Request::segment(3) == 'exam') active @endif">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Exam</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ url('admin/examinations/exam_schedule') }}"
                                    class="nav-link @if (Request::segment(3) == 'exam_schedule') active @endif">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Exam Schedule</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ url('admin/examinations/marks_register') }}"
                                    class="nav-link @if (Request::segment(3) == 'marks_register') active @endif">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Marks Register</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ url('admin/examinations/marks_grade') }}"
                                    class="nav-link @if (Request::segment(3) == 'marks_grade') active @endif">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Marks Grade</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item @if (Request::segment(2) == 'attendance') menu-is-opening menu-open @endif">
                        <a href="#" class="nav-link @if (Request::segment(2) == 'attendance') active @endif">
                            <i class="nav-icon fas fa-table"></i>
                            <p>
                                Attendance
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ url('admin/attendance/student') }}"
                                    class="nav-link @if (Request::segment(3) == 'student') active @endif">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Student Attendance</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ url('admin/attendance/report') }}"
                                    class="nav-link @if (Request::segment(2) == 'attendance' && Request::segment(3) == 'report') active @endif">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Attendance Report</p>
                                </a>
                            </li>

                        </ul>
                    </li>

                    <li class="nav-item @if (Request::segment(2) == 'communicate') menu-is-opening menu-open @endif">
                        <a href="#" class="nav-link @if (Request::segment(2) == 'communicate') active @endif">
                            <i class="nav-icon fas fa-table"></i>
                            <p>
                                Communicate
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ url('admin/communicate/notice_board') }}"
                                    class="nav-link @if (Request::segment(3) == 'notice_board') active @endif">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Notice Board</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ url('admin/communicate/send_email') }}"
                                    class="nav-link @if (Request::segment(3) == 'send_email') active @endif">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Send Email</p>
                                </a>
                            </li>

                        </ul>
                    </li>

                    <li class="nav-item @if (Request::segment(2) == 'homework') menu-is-opening menu-open @endif">
                        <a href="#" class="nav-link @if (Request::segment(2) == 'homework') active @endif">
                            <i class="nav-icon fas fa-table"></i>
                            <p>
                                Homework
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ url('admin/homework/homework') }}"
                                    class="nav-link @if (Request::segment(3) == 'homework') active @endif">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Homework</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ url('admin/homework/report') }}"
                                    class="nav-link @if (Request::segment(2) == 'homework' && Request::segment(3) == 'report') active @endif">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Homework Report</p>
                                </a>
                            </li>

                        </ul>
                    </li>

                    <li class="nav-item">
                        <a href="{{ url('admin/account') }}"
                            class="nav-link @if (Request::segment(2) == 'account') active @endif">
                            <i class="nav-icon far fa-user"></i>
                            <p>
                                My Account
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ url('admin/change_password') }}"
                            class="nav-link @if (Request::segment(2) == 'change_password') active @endif">
                            <i class="nav-icon far fa-user"></i>
                            <p>
                                Change Password
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ url('logout') }}" class="nav-link">
                            <i class="nav-icon far fa-user"></i>
                            <p>
                                Logout
                            </p>
                        </a>
                    </li>
                @elseif(Auth::user()->user_type == 2)
                    <li class="nav-item">
                        <a href="{{ url('teacher/dashboard') }}"
                            class="nav-link @if (Request::segment(2) == 'dashboard') active @endif">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>
                                Dashboard
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ url('teacher/my_student') }}"
                            class="nav-link @if (Request::segment(2) == 'my_student') active @endif">
                            <i class="nav-icon far fa-user"></i>
                            <p>
                                My Student
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ url('teacher/my_class_subject') }}"
                            class="nav-link @if (Request::segment(2) == 'my_class_subject') active @endif">
                            <i class="nav-icon far fa-user"></i>
                            <p>
                                My Class & Subject
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ url('teacher/my_exam_timetable') }}"
                            class="nav-link @if (Request::segment(2) == 'my_exam_timetable') active @endif">
                            <i class="nav-icon far fa-user"></i>
                            <p style="text-align: center;">
                                Exam TimeTable
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ url('teacher/marks_register') }}"
                            class="nav-link @if (Request::segment(2) == 'marks_register') active @endif">
                            <i class="nav-icon far fa-user"></i>
                            <p style="text-align: center;">
                                Marks Register
                            </p>
                        </a>
                    </li>

                    <li class="nav-item @if (Request::segment(2) == 'attendance') menu-is-opening menu-open @endif">
                        <a href="#" class="nav-link @if (Request::segment(2) == 'attendance') active @endif">
                            <i class="nav-icon fas fa-table"></i>
                            <p>
                                Attendance
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ url('teacher/attendance/student') }}"
                                    class="nav-link @if (Request::segment(3) == 'student') active @endif">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Student Attendance</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ url('teacher/attendance/report') }}"
                                    class="nav-link @if (Request::segment(3) == 'report') active @endif">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Attendance Report</p>
                                </a>
                            </li>

                        </ul>
                    </li>

                    <li class="nav-item @if (Request::segment(2) == 'homework') menu-is-opening menu-open @endif">
                        <a href="#" class="nav-link @if (Request::segment(2) == 'homework') active @endif">
                            <i class="nav-icon fas fa-table"></i>
                            <p>
                                Homework
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ url('teacher/homework/homework') }}"
                                    class="nav-link @if (Request::segment(3) == 'homework') active @endif">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Homework</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a href="{{ url('teacher/my_notice_board') }}"
                            class="nav-link @if (Request::segment(2) == 'my_notice_board') active @endif">
                            <i class="nav-icon far fa-user"></i>
                            <p>
                                My Notice Board
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ url('teacher/account') }}"
                            class="nav-link @if (Request::segment(2) == 'account') active @endif">
                            <i class="nav-icon far fa-user"></i>
                            <p>
                                My Account
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ url('teacher/change_password') }}"
                            class="nav-link @if (Request::segment(2) == 'change_password') active @endif">
                            <i class="nav-icon far fa-user"></i>
                            <p>
                                Change Password
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ url('logout') }}" class="nav-link">
                            <i class="nav-icon far fa-user"></i>
                            <p>
                                Logout
                            </p>
                        </a>
                    </li>
                @elseif(Auth::user()->user_type == 3)
                    <li class="nav-item">
                        <a href="{{ url('student/dashboard') }}"
                            class="nav-link @if (Request::segment(2) == 'dashboard') active @endif">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>
                                Dashboard
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ url('student/my_subject') }}"
                            class="nav-link @if (Request::segment(2) == 'my_subject') active @endif">
                            <i class="nav-icon far fa-user"></i>
                            <p>
                                My Subject
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ url('student/my_timetable') }}"
                            class="nav-link @if (Request::segment(2) == 'my_timetable') active @endif">
                            <i class="nav-icon far fa-user"></i>
                            <p>
                                My Class TimeTable
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ url('student/fees_collection') }}"
                            class="nav-link @if (Request::segment(2) == 'fees_collection') active @endif">
                            <i class="nav-icon far fa-user"></i>
                            <p>
                                Fees Collection
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ url('student/my_exam_timetable') }}"
                            class="nav-link @if (Request::segment(2) == 'my_exam_timetable') active @endif">
                            <i class="nav-icon far fa-user"></i>
                            <p>
                                My Exam TimeTable
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ url('student/my_exam_result') }}"
                            class="nav-link @if (Request::segment(2) == 'my_exam_result') active @endif">
                            <i class="nav-icon far fa-user"></i>
                            <p>
                                My Exam Result
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ url('student/my_attendance') }}"
                            class="nav-link @if (Request::segment(2) == 'my_attendance') active @endif">
                            <i class="nav-icon far fa-user"></i>
                            <p>
                                My Attendance
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ url('student/my_notice_board') }}"
                            class="nav-link @if (Request::segment(2) == 'my_notice_board') active @endif">
                            <i class="nav-icon far fa-user"></i>
                            <p>
                                My Notice Board
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ url('student/my_homework') }}"
                            class="nav-link @if (Request::segment(2) == 'my_homework') active @endif">
                            <i class="nav-icon far fa-user"></i>
                            <p>
                                My Homework
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ url('student/my_submitted_homework') }}"
                            class="nav-link @if (Request::segment(2) == 'my_submitted_homework') active @endif">
                            <i class="nav-icon far fa-user"></i>
                            <p>
                                Submitted Homework
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ url('student/account') }}"
                            class="nav-link @if (Request::segment(2) == 'account') active @endif">
                            <i class="nav-icon far fa-user"></i>
                            <p>
                                My Account
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ url('student/change_password') }}"
                            class="nav-link @if (Request::segment(2) == 'change_password') active @endif">
                            <i class="nav-icon far fa-user"></i>
                            <p>
                                Change Password
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ url('logout') }}" class="nav-link">
                            <i class="nav-icon far fa-user"></i>
                            <p>
                                Logout
                            </p>
                        </a>
                    </li>
                @elseif(Auth::user()->user_type == 4)
                    <li class="nav-item">
                        <a href="{{ url('parent/dashboard') }}"
                            class="nav-link @if (Request::segment(2) == 'dashboard') active @endif">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>
                                Dashboard
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ url('parent/my_student') }}"
                            class="nav-link @if (Request::segment(2) == 'my_student') active @endif">
                            <i class="nav-icon far fa-user"></i>
                            <p>
                                My Son/Daughter
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ url('parent/my_notice_board') }}"
                            class="nav-link @if (Request::segment(2) == 'my_notice_board') active @endif">
                            <i class="nav-icon far fa-user"></i>
                            <p>
                                My Notice Board
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ url('parent/account') }}"
                            class="nav-link @if (Request::segment(2) == 'account') active @endif">
                            <i class="nav-icon far fa-user"></i>
                            <p>
                                My Account
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ url('parent/change_password') }}"
                            class="nav-link @if (Request::segment(2) == 'change_password') active @endif">
                            <i class="nav-icon far fa-user"></i>
                            <p>
                                Change Password
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ url('logout') }}" class="nav-link">
                            <i class="nav-icon far fa-user"></i>
                            <p>
                                Logout
                            </p>
                        </a>
                    </li>
                @endif
            </ul>
        </nav>
    </div>
</aside>
