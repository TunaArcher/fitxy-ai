<footer class="adminuiux-mobile-footer hide-on-scrolldown style-1">
    <div class="container">
        <ul class="nav nav-pills nav-justified">
            <li class="nav-item">
                <a class="nav-link" href="<?php echo base_url('/'); ?>"><span><i class="nav-icon bi bi-columns-gap"></i>
                        <span class="nav-text">หน้าแรก</span></span></a>
            </li>
            <li class="nav-item">
                <a href="<?php echo base_url('/menu'); ?>" class="nav-link"><span><svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="nav-icon"
                            viewBox="0 0 19.679 18.075">
                            <g id="meal-icon" transform="translate(-125.935 -162.02)">
                                <path
                                    id="Path_42"
                                    data-name="Path 42"
                                    d="M3755.531,2500.078a2.477,2.477,0,0,1,2.329-2.3c2.31-.352,2.858-.352,3.6-1.331s2.232-2.78,4.268-1.605,1.879,3.093,3.446,3.367a2.323,2.323,0,0,1,1.762,2"
                                    transform="translate(-3628 -2329)"
                                    fill="none"
                                    stroke=""
                                    stroke-width="1" />
                                <path
                                    id="Path_43"
                                    data-name="Path 43"
                                    d="M3767.218,2496.091c.744-.862,3.093-3.6,3.093-3.6s1.1-1.488,1.958-.744-.392,1.958-.392,1.958L3768,2497.07"
                                    transform="translate(-3628 -2329)"
                                    fill="none"
                                    stroke=""
                                    stroke-width="1" />
                                <path
                                    id="Path_45"
                                    data-name="Path 45"
                                    d="M145.615,171.544H126.988s1.28,8.143,8.882,8.051,8.871-7.74,8.871-7.74"
                                    fill="none"
                                    stroke=""
                                    stroke-width="1" />
                                <path
                                    id="Path_46"
                                    data-name="Path 46"
                                    d="M3757.935,2506.125h12.049"
                                    transform="translate(-3628 -2329)"
                                    fill="none"
                                    stroke=""
                                    stroke-width="1" />
                                <path
                                    id="Path_47"
                                    data-name="Path 47"
                                    d="M3757.935,2506.125h12.049"
                                    transform="translate(-3632 -2334.582)"
                                    fill="none"
                                    stroke=""
                                    stroke-width="1" />
                            </g>
                        </svg>
                        <span class="nav-text">กิน</span></span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo base_url('/workout'); ?>"><span><svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="nav-icon"
                            viewBox="0 0 20 10">
                            <g id="workout-icon" transform="translate(-87 -157)">
                                <g
                                    id="Rectangle_32"
                                    data-name="Rectangle 32"
                                    transform="translate(87 159)"
                                    fill="none"
                                    stroke=""
                                    stroke-width="1">
                                    <rect width="4" height="8" rx="2" stroke="none" />
                                    <rect
                                        x="0.5"
                                        y="0.5"
                                        width="3"
                                        height="7"
                                        rx="1.5"
                                        fill="none" />
                                </g>
                                <g
                                    id="Rectangle_36"
                                    data-name="Rectangle 36"
                                    transform="translate(93 161)"
                                    fill="none"
                                    stroke=""
                                    stroke-width="1">
                                    <rect width="8" height="4" stroke="none" />
                                    <rect x="0.5" y="0.5" width="7" height="3" fill="none" />
                                </g>
                                <g
                                    id="Rectangle_34"
                                    data-name="Rectangle 34"
                                    transform="translate(90 157)"
                                    fill="none"
                                    stroke=""
                                    stroke-width="1">
                                    <rect width="4" height="12" rx="2" stroke="none" />
                                    <rect
                                        x="0.5"
                                        y="0.5"
                                        width="3"
                                        height="11"
                                        rx="1.5"
                                        fill="none" />
                                </g>
                                <g
                                    id="Rectangle_35"
                                    data-name="Rectangle 35"
                                    transform="translate(100 157)"
                                    fill="none"
                                    stroke=""
                                    stroke-width="1">
                                    <rect width="4" height="12" rx="2" stroke="none" />
                                    <rect
                                        x="0.5"
                                        y="0.5"
                                        width="3"
                                        height="11"
                                        rx="1.5"
                                        fill="none" />
                                </g>
                                <g
                                    id="Rectangle_33"
                                    data-name="Rectangle 33"
                                    transform="translate(103 159)"
                                    fill="none"
                                    stroke=""
                                    stroke-width="1">
                                    <rect width="4" height="8" rx="2" stroke="none" />
                                    <rect
                                        x="0.5"
                                        y="0.5"
                                        width="3"
                                        height="7"
                                        rx="1.5"
                                        fill="none" />
                                </g>
                            </g>
                        </svg>
                        <span class="nav-text">ออกกำลังกาย</span></span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo base_url('/summary'); ?>"><span><i class="nav-icon bi bi-graph-up-arrow"></i>
                        <span class="nav-text">สรุป</span></span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo base_url('/profile'); ?>">
                    <span>
                        <figure
                            class="avatar avatar-20 rounded-circle coverimg align-middle mb-2">
                            <img src="<?php echo session()->get('user')->profile; ?>" alt="" />
                        </figure>
                        <br /><span class="nav-text">หาเพื่อนร่วมฟิต</span>
                    </span>
                </a>
            </li>
        </ul>
    </div>
</footer>