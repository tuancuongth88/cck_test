const GENERAL_AFFAIRS = 'general_affairs';
const VEHICLE_ANALYSIS = 'vehicle_analysis';
const INFORMATION_SYSTEMS = 'information_systems';
const CENTER_MANAGER = 'center_manager';
const VIEWER = 'viewer';

const ROLE = {
  // Key role
  GENERAL_AFFAIRS,
  VEHICLE_ANALYSIS,
  INFORMATION_SYSTEMS,
  CENTER_MANAGER,
  VIEWER,
  TYPE_SUPER_ADMIN: 1,
  TYPE_COMPANY_ADMIN: 2,
  TYPE_HR_MANAGER: 3,
  TYPE_COMPANY: 4,
  TYPE_HR: 5,

  // List role
  LIST_ROLE: [
    GENERAL_AFFAIRS,
    VEHICLE_ANALYSIS,
    INFORMATION_SYSTEMS,
    CENTER_MANAGER,
    VIEWER,
  ],
};

export default ROLE;
