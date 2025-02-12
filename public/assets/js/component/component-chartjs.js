/*! For license information please see component-chartjs.js.LICENSE.txt */
"use strict";document.addEventListener("DOMContentLoaded",(function(){window.randomScalingFactor=function(){return Math.round(20*Math.random())};var a=document.getElementById("areachartblue1").getContext("2d"),o=a.createLinearGradient(0,0,0,65);o.addColorStop(0,"rgba(1, 94, 194, 0.85)"),o.addColorStop(1,"rgba(0, 197, 221, 0)");var r={type:"bar",data:{labels:["1","2","3","4","5","7","8","9","10","11","12","13","14"],datasets:[{label:"# of Votes",data:[randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor()],radius:0,backgroundColor:o,borderColor:"#015EC2",borderWidth:0,borderRadius:4,fill:!0,tension:.5}]},options:{maintainAspectRatio:!1,plugins:{legend:{display:!1},tooltip:{enabled:!1}},scales:{y:{display:!1,beginAtZero:!0},x:{display:!1}}}},n=(new Chart(a,r),document.getElementById("areachartred1").getContext("2d")),t=n.createLinearGradient(0,0,0,65);t.addColorStop(0,"rgba(240, 61, 79, 0.85)"),t.addColorStop(1,"rgba(255, 223, 220, 0)");var d={type:"bar",data:{labels:["1","2","3","4","5","7","8","9","10","11","12","13","14"],datasets:[{label:"# of Votes",data:[randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor()],radius:0,backgroundColor:t,borderColor:"#f03d4f",borderWidth:0,borderRadius:4,fill:!0,tension:.5}]},options:{maintainAspectRatio:!1,plugins:{legend:{display:!1},tooltip:{enabled:!1}},scales:{y:{display:!1,beginAtZero:!0},x:{display:!1}}}},c=(new Chart(n,d),document.getElementById("areachartgreen1").getContext("2d")),e=c.createLinearGradient(0,0,0,65);e.addColorStop(0,"rgba(145, 195, 0, 0.85)"),e.addColorStop(1,"rgba(176, 197, 0, 0)");var l={type:"bar",data:{labels:["1","2","3","4","5","7","8","9","10","11","12","13","14"],datasets:[{label:"# of Votes",data:[randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor()],radius:0,backgroundColor:e,borderColor:"#91C300",borderWidth:0,borderRadius:4,fill:!0,tension:.5}]},options:{maintainAspectRatio:!1,plugins:{legend:{display:!1},tooltip:{enabled:!1}},scales:{y:{display:!1,beginAtZero:!0},x:{display:!1}}}},i=(new Chart(c,l),document.getElementById("areachartyellow1").getContext("2d")),g=i.createLinearGradient(0,0,0,65);g.addColorStop(0,"rgba(253, 100, 0, 0.85)"),g.addColorStop(1,"rgba(253, 186, 0, 0)");var m={type:"bar",data:{labels:["1","2","3","4","5","7","8","9","10","11","12","13","14"],datasets:[{label:"# of Votes",data:[randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor()],radius:0,backgroundColor:g,borderColor:"#fdba00",borderWidth:0,borderRadius:4,fill:!0,tension:.5}]},options:{maintainAspectRatio:!1,plugins:{legend:{display:!1},tooltip:{enabled:!1}},scales:{y:{display:!1,beginAtZero:!0},x:{display:!1}}}},s=(new Chart(i,m),document.getElementById("summarychart").getContext("2d")),S=(s.createLinearGradient(0,0,0,120),{type:"line",data:{labels:["10:30","11:00","11:30","12:00","12:30","01:00","01:30"],datasets:[{label:"# of Votes",data:[randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor()],radius:0,backgroundColor:"rgba(0, 0, 0, 0)",borderColor:"#5840ef",borderWidth:2,fill:!0,tension:.5}]},options:{maintainAspectRatio:!1,plugins:{legend:{display:!1}},scales:{y:{display:!1,beginAtZero:!0},x:{grid:{display:!1},display:!0,beginAtZero:!0}}}}),F=new Chart(s,S);setInterval((function(){S.data.datasets.forEach((function(a){a.data=a.data.map((function(){return randomScalingFactor()}))})),F.update()}),3e3)}));