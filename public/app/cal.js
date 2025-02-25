const formData = {
  gender: null,
  age: null,
  weight: null,
  height: null,
  exercise: null,
  target: null,
  calPerDay: null,
};

let tdee = 0;

let goals = {
  ลดน้ำหนักอย่างมาก: {
    cal: tdee - 1000,
    weight: -1,
    badge: "badge badge-light text-bg-danger",
  },
  ลดน้ำหนัก: {
    cal: tdee - 500,
    weight: -0.5,
    badge: "badge badge-light text-bg-warning",
  },
  ลดน้ำหนักเล็กน้อย: {
    cal: tdee - 250,
    weight: -0.25,
    badge: "badge badge-light text-bg-success",
  },
  รักษาน้ำหนัก: {
    cal: tdee,
    weight: 0,
    badge: "badge badge-light text-bg-secondary",
  },
  เพิ่มน้ำหนักเล็กน้อย: {
    cal: tdee + 250,
    weight: 0.25,
    badge: "badge badge-light text-bg-success",
  },
  เพิ่มน้ำหนัก: {
    cal: tdee + 500,
    weight: 0.5,
    badge: "badge badge-light text-bg-warning",
  },
  เพิ่มน้ำหนักอย่างมาก: {
    cal: tdee + 1000,
    weight: 1,
    badge: "badge badge-light text-bg-danger",
  },
};

document.addEventListener("DOMContentLoaded", function () {
  const totalSteps = 5;
  let currentStep = 0;

  const steps = document.querySelectorAll(".step");
  const progressBar = document.querySelector(".progress-bar");
  const backBtn = document.getElementById("backBtn");
  const nextBtn = document.getElementById("nextBtn");

  // อัปเดต Progress Bar
  function updateProgressBar() {
    const percentage = ((currentStep + 1) / totalSteps) * 100;
    progressBar.style.width = percentage + "%";
    progressBar.textContent = `Step ${currentStep + 1} of ${totalSteps}`;
  }

  // ฟังก์ชันปรับตำแหน่ง scroll ของ picker (ใช้ใน Step 2-4)
  function recalcPickerScroll() {
    const activeStep = steps[currentStep];
    const picker = activeStep.querySelector(".picker");
    if (picker) {
      const selectedItem = picker.querySelector(".selected");
      if (selectedItem) {
        picker.scrollTop =
          selectedItem.offsetTop -
          picker.offsetHeight / 2 +
          selectedItem.offsetHeight / 2;
      }
    }
  }

  // แสดงเฉพาะ Step ที่ต้องการ และเมื่อแสดงแล้วปรับตำแหน่ง scroll หากมี
  function showStep(index) {
    steps.forEach((step, i) => {
      step.classList.toggle("active", i === index);
    });

    // ถ้าอยู่ที่ Step 1 (index 0) ให้ซ่อนปุ่ม Back
    if (index === 0) {
      backBtn.style.display = "none";
    } else {
      backBtn.style.display = "inline-block";
    }

    backBtn.disabled = index === 0;
    nextBtn.innerHTML =
      index === totalSteps - 1
        ? "ยืนยันคำนวน"
        : `ถัดไป <i class="bi bi-arrow-right ms-2"></i>`;
    updateNextButtonState();
    updateProgressBar();
    setTimeout(recalcPickerScroll, 0);
  }

  // เปิดใช้งานปุ่ม Next เมื่อมีการเลือกในแต่ละ Step แล้ว
  function updateNextButtonState() {
    let enabled = false;
    if (currentStep === 0) {
      enabled = formData.gender !== null;
    } else if (currentStep === 1) {
      enabled = formData.age !== null;
    } else if (currentStep === 2) {
      enabled = formData.weight !== null;
    } else if (currentStep === 3) {
      enabled = formData.height !== null;
    } else if (currentStep === 4) {
      enabled = formData.exercise !== null;
    }
    nextBtn.disabled = !enabled;
  }

  // -------------------------------
  // Step 1: เลือกเพศ
  const genderOptions = document.querySelectorAll(".gender-option");
  genderOptions.forEach((option) => {
    option.addEventListener("click", function () {
      genderOptions.forEach((opt) => opt.classList.remove("selected"));
      this.classList.add("selected");
      formData.gender = this.dataset.value;
      updateNextButtonState();
    });
  });

  // -------------------------------
  // ฟังก์ชันสร้าง Scroll Picker สำหรับ Step 2-4
  function initScrollPicker(pickerId, values, formKey, defaultValue) {
    const picker = document.getElementById(pickerId);
    let defaultIndex;
    if (defaultValue !== undefined) {
      defaultIndex = values.indexOf(defaultValue);
      if (defaultIndex === -1) {
        defaultIndex = Math.floor(values.length / 2);
      }
    } else {
      defaultIndex = Math.floor(values.length / 2);
    }
    values.forEach((val, index) => {
      const div = document.createElement("div");
      div.textContent = val;
      div.dataset.value = val;
      if (index === defaultIndex) {
        div.classList.add("selected");
        formData[formKey] = val;
      }
      picker.appendChild(div);
    });

    const items = picker.querySelectorAll("div");
    function updateSelection() {
      const center = picker.scrollTop + picker.offsetHeight / 2;
      items.forEach((item) => {
        const itemCenter = item.offsetTop + item.offsetHeight / 2;
        const distance = Math.abs(center - itemCenter);
        if (distance < item.offsetHeight / 2) {
          items.forEach((el) => el.classList.remove("selected"));
          item.classList.add("selected");
          formData[formKey] = item.dataset.value;
        }
      });
      updateNextButtonState();
    }
    picker.addEventListener("scroll", function () {
      clearTimeout(picker.dataset.timer);
      picker.dataset.timer = setTimeout(updateSelection, 100);
    });
  }

  // -------------------------------
  // Step 2: เลือกอายุ (18-80) โดยระบุ default เป็น 20
  const ageValues = [];
  for (let i = 12; i <= 80; i++) {
    ageValues.push(i);
  }
  initScrollPicker("agePicker", ageValues, "age", 20);

  // Step 3: เลือกน้ำหนัก (30-150) โดยระบุ default เป็น 50
  const weightValues = [];
  for (let i = 20; i <= 150; i++) {
    weightValues.push(i);
  }
  initScrollPicker("weightPicker", weightValues, "weight", 60);

  // Step 4: เลือกส่วนสูง (150-200) โดยระบุ default เป็น 150
  const heightValues = [];
  for (let i = 120; i <= 200; i++) {
    heightValues.push(i);
  }
  initScrollPicker("heightPicker", heightValues, "height", 161);

  // -------------------------------
  // Step 5: เลือกระดับการออกกำลังกาย (ใช้ Option)
  const exerciseOptions = document.querySelectorAll("#exerciseOptions .option");

  // กำหนดค่า default จาก option ที่มี class "selected" อยู่แล้ว
  const defaultExercise = document.querySelector(
    "#exerciseOptions .option.selected"
  );
  if (defaultExercise) {
    formData.exercise = defaultExercise
      .querySelector(".activity-details h3")
      .textContent.trim();
    updateNextButtonState();
  }

  exerciseOptions.forEach((option) => {
    option.addEventListener("click", function () {
      // ลบ class selected จาก option ทั้งหมด
      exerciseOptions.forEach((opt) => opt.classList.remove("selected"));
      // เพิ่ม class selected ให้กับ option ที่ถูกคลิก
      this.classList.add("selected");
      // ดึงค่าจาก <h3> ภายใน option และเก็บไว้ใน formData.exercise
      formData.exercise = this.querySelector(
        ".activity-details h3"
      ).textContent.trim();
      updateNextButtonState();
    });
  });

  // -------------------------------
  // ปุ่ม Navigation
  nextBtn.addEventListener("click", function () {
    if (currentStep === totalSteps - 1) {
      // เมื่อคลิก Submit ในขั้นตอนสุดท้าย แทนที่จะ alert ให้แสดง Processing Overlay
      // ซ่อนฟอร์ม
      document.querySelector(".container").style.display = "none";
      // แสดง Overlay พร้อม Spinner
      const overlay = document.getElementById("processingOverlay");
      overlay.style.display = "flex";

      // จำลองการประมวลผล (เช่น 3 วินาที) แล้วแสดงข้อความ "การประมวลผลเสร็จสิ้น!"
      setTimeout(() => {
        overlay.style.display = "none";
        overlay.innerHTML = '<div class="spinner" style="display:none;"></div>';
        $("#main-content").hide();
        calculateTDEE(formData);
      }, 1500);

      console.log(formData);
    } else {
      currentStep++;
      showStep(currentStep);
    }
  });

  backBtn.addEventListener("click", function () {
    if (currentStep > 0) {
      currentStep--;
      showStep(currentStep);
    }
  });

  // แสดง Step แรก
  showStep(currentStep);
});

// function calculateTDEE(formData) {
//   let gender = formData.gender;
//   let age = formData.age;
//   let weight = formData.weight;
//   let height = formData.height;
//   let activity = 0;

//   switch (formData.exercise) {
//     // ไม่ออกกำลังกายเลยหรือน้อยมาก
//     case "Sedentary":
//       activity = 1.2;
//       break;

//     // ออกกำลังกายเบา ๆ 1-3 ครั้ง/สัปดาห์
//     case "Lightly Active":
//       activity = 1.375;
//       break;

//     // ออกกำลังกายระดับปานกลาง 4-5 ครั้ง/สัปดาห์
//     case "Moderately Active":
//       activity = 1.55;
//       break;

//     // ออกกำลังกายทุกวันหรือหนัก 3-4 ครั้ง/สัปดาห์
//     case "Very Active":
//       activity = 1.725;
//       break;

//     // ออกกำลังกายหนักมากทุกวันหรือทำงานหนัก
//     case "Athlete":
//       activity = 2.2;
//       break;
//   }

//   let bmr =
//     gender === "male"
//       ? 10 * weight + 6.25 * height - 5 * age + 5
//       : 10 * weight + 6.25 * height - 5 * age - 161;

//   tdee = bmr * activity;

//   document.getElementById("tdee-result").innerHTML = `<strong>${Math.round(
//     tdee
//   )} แคลอรี่ต่อวัน</strong>`;

//   goals = {
//     ลดน้ำหนักอย่างมาก: {
//       cal: tdee - 1000,
//       weight: -1,
//       badge: "badge badge-light text-bg-danger",
//     },
//     ลดน้ำหนัก: {
//       cal: tdee - 500,
//       weight: -0.5,
//       badge: "badge badge-light text-bg-warning",
//     },
//     ลดน้ำหนักเล็กน้อย: {
//       cal: tdee - 250,
//       weight: -0.25,
//       badge: "badge badge-light text-bg-success",
//     },
//     รักษาน้ำหนัก: {
//       cal: tdee,
//       weight: 0,
//       badge: "badge badge-light text-bg-secondary",
//     },
//     เพิ่มน้ำหนักเล็กน้อย: {
//       cal: tdee + 250,
//       weight: 0.25,
//       badge: "badge badge-light text-bg-success",
//     },
//     เพิ่มน้ำหนัก: {
//       cal: tdee + 500,
//       weight: 0.5,
//       badge: "badge badge-light text-bg-warning",
//     },
//     เพิ่มน้ำหนักอย่างมาก: {
//       cal: tdee + 1000,
//       weight: 1,
//       badge: "badge badge-light text-bg-danger",
//     },
//   };

//   let wrapperResultTdee = document.getElementById("wrapper-result-tdee");
//   wrapperResultTdee.style.display = "block";

//   Object.keys(goals).forEach((goal, index) => {
//     let percentage = ((goals[goal].cal / tdee) * 100).toFixed(0);
//     let target = `${goal} ${goals[goal].weight} กิโล/อาทิตย์`;
//     let gradientClass = `bg-gradient-${index + 1}`; // เปลี่ยนเลขต่อท้าย

//     let html = `
//     <div class="card adminuiux-card border-0 ${gradientClass} mt-2">
//         <div class="card-body">
//             <div class="row">
//                 <div class="col-4">
//                     <figure class="height-90 w-100 rounded coverimg mb-0" style="background-image: url(&quot;assets/img/fitness/image-${
//                       index + 1
//                     }.jpg&quot;);">
//                         <img src="assets/img/fitness/image-${
//                           index + 1
//                         }.jpg" alt="" style="display: none;">
//                     </figure>
//                     </div>
//                     <div class="col-8">
//                     <h6 class="text-truncated"><span class="${
//                       goals[goal].badge
//                     }">${goal}</span> | ${Math.round(
//       goals[goal].cal
//     )} แคล/วัน</h6>
//                     <p class="text-secondary fs-14 mb-2">
//                         <span class="me-1"><i class="bi bi-clock me-1"></i> ${
//                           goals[goal].weight
//                         } กิโล/อาทิตย์</span>
//                         <span class="me-1"><i class="bi bi-fire me-1"></i> ${percentage}%</span>
//                     </p>
//                     <button class="btn btn-sm btn-primary w-100 btn-select-target" data-title="${target}" data-cal="${Math.round(
//       goals[goal].cal
//     )}">
//                         <i class="bi bi-play me-1"></i> เลือก
//                     </button>
//                 </div>
//             </div>
//         </div>
//     </div>
//     `;

//     wrapperResultTdee.innerHTML += html;
//   });
// }

function calculateTDEE(formData) {
  let gender = formData.gender;
  let age = formData.age;
  let weight = formData.weight;
  let height = formData.height;
  let activity = 0;

  // ใช้ lifestyle แทน exercise
  switch (formData.exercise) {
    case "Office-Based":
      activity = 1.2;
      break;
    case "Active Commuter":
      activity = 1.375;
      break;
    case "Active & Health":
      activity = 1.55;
      break;
    case "High-Performance":
      activity = 1.9;
      break;
    case "Athlete & High-Intensity":
      activity = 2.4;
      break;
    default:
      activity = 1.2; // เผื่อกรณีไม่มีค่า
  }

  // คำนวณ BMR ตามเพศ
  let bmr =
    gender === "male"
      ? 10 * weight + 6.25 * height - 5 * age + 5
      : 10 * weight + 6.25 * height - 5 * age - 161;

  // คำนวณ TDEE
  let tdee = bmr * activity;

  document.getElementById("tdee-result").innerHTML = `<strong>${Math.round(
    tdee
  )} แคลอรี่ต่อวัน</strong>`;

  // ตั้งเป้าหมายแคลอรี่สำหรับลด/เพิ่มน้ำหนัก
  goals = {
    ลดน้ำหนักอย่างมาก: {
      cal: tdee - 1000,
      weight: -1,
      badge: "badge text-bg-danger",
    },
    ลดน้ำหนัก: {
      cal: tdee - 500,
      weight: -0.5,
      badge: "badge text-bg-warning",
    },
    ลดน้ำหนักเล็กน้อย: {
      cal: tdee - 250,
      weight: -0.25,
      badge: "badge text-bg-success",
    },
    รักษาน้ำหนัก: { cal: tdee, weight: 0, badge: "badge text-bg-secondary" },
    เพิ่มน้ำหนักเล็กน้อย: {
      cal: tdee + 250,
      weight: 0.25,
      badge: "badge text-bg-success",
    },
    เพิ่มน้ำหนัก: {
      cal: tdee + 500,
      weight: 0.5,
      badge: "badge text-bg-warning",
    },
    เพิ่มน้ำหนักอย่างมาก: {
      cal: tdee + 1000,
      weight: 1,
      badge: "badge text-bg-danger",
    },
  };

  let wrapperResultTdee = document.getElementById("wrapper-result-tdee");
  wrapperResultTdee.style.display = "block";
  wrapperResultTdee.innerHTML = ""; // ล้างค่าก่อนแสดงใหม่

  // แสดงผลลัพธ์เป้าหมาย
  Object.keys(goals).forEach((goal, index) => {
    let percentage = ((goals[goal].cal / tdee) * 100).toFixed(0);
    let target = `${goal} ${goals[goal].weight} กิโล/อาทิตย์`;
    let gradientClass = `bg-gradient-${index + 1}`;

    let html = `
    <div class="card adminuiux-card border-0 ${gradientClass} mt-2">
        <div class="card-body">
            <div class="row">
                <div class="col-4">
                    <figure class="height-90 w-100 rounded coverimg mb-0" style="background-image: url('assets/img/fitness/image-${
                      index + 1
                    }.jpg');">
                        <img src="assets/img/fitness/image-${
                          index + 1
                        }.jpg" alt="" style="display: none;">
                    </figure>
                    </div>
                    <div class="col-8">
                    <h6 class="text-truncated"><span class="${
                      goals[goal].badge
                    }">${goal}</span> | ${Math.round(
      goals[goal].cal
    )} แคล/วัน</h6>
                    <p class="text-secondary fs-14 mb-2">
                        <span class="me-1"><i class="bi bi-clock me-1"></i> ${
                          goals[goal].weight
                        } กิโล/อาทิตย์</span>
                        <span class="me-1"><i class="bi bi-fire me-1"></i> ${percentage}%</span>
                    </p>
                    <button class="btn btn-sm btn-primary w-100 btn-select-target" data-title="${target}" data-cal="${Math.round(
      goals[goal].cal
    )}">
                        <i class="bi bi-play me-1"></i> เลือก
                    </button>
                </div>
            </div>
        </div>
    </div>
    `;

    wrapperResultTdee.innerHTML += html;
  });
}

const $wrapperResultTdee = $("#wrapper-result-tdee");

$wrapperResultTdee.on("click", ".btn-select-target", function () {
  let $me = $(this);

  formData.target = $me.data("title");
  formData.calPerDay = $me.data("cal");

  console.log(goals["รักษาน้ำหนัก"].cal);
  // เพิ่มค่า cal สำหรับการรักษาน้ำหนัก
  formData.maintenanceCal = goals["รักษาน้ำหนัก"].cal;

  console.log(formData);

  $wrapperResultTdee.find(".btn-select-target").prop("disabled", true);

  // ส่งข้อมูลไปที่เซิร์ฟเวอร์ผ่าน AJAX
  $.ajax({
    url: `${window.serverUrl}/calculate`,
    type: "POST",
    data: JSON.stringify(formData),
    contentType: "application/json",
    success: function (response) {
      let data = response.data;

      if (response.success) {
        location.href = serverUrl;
      }

      $wrapperResultTdee.find(".btn-select-target").prop("disabled", false);
    },
    error: function (xhr, status, error) {},
  });
});
