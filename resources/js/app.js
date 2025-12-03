import './bootstrap';
import '../css/app.css'; 
import { createApp } from "vue";

import SystemUsageChart from "./Components/SystemUsageChart.vue";
import ProfileModal from "./Components/ProfileModal.vue";
import AnnouncementModal from "./Components/AnnouncementModal.vue";
import ReportModal from "./Components/ReportModal.vue";

const app = createApp({});
app.component("system-usage-chart", SystemUsageChart);
app.component("profile-modal", ProfileModal);
app.component("announcement-modal", AnnouncementModal);
app.component("report-modal", ReportModal);
app.mount("#app");
 