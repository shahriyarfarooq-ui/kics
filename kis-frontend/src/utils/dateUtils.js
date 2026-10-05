// Format date to readable string
export const formatDate = (date, format = 'MMM D, YYYY') => {
  if (!date) return '';
  const d = new Date(date);
  if (isNaN(d.getTime())) return '';
  
  const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
  const month = months[d.getMonth()];
  const day = d.getDate();
  const year = d.getFullYear();
  const hours = d.getHours().toString().padStart(2, '0');
  const minutes = d.getMinutes().toString().padStart(2, '0');
  
  return format
    .replace('MMMM', month)
    .replace('MMM', month)
    .replace('MM', String(d.getMonth() + 1).padStart(2, '0'))
    .replace('M', d.getMonth() + 1)
    .replace('DDDD', ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'][d.getDay()])
    .replace('DDD', ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'][d.getDay()])
    .replace('DD', String(day).padStart(2, '0'))
    .replace('D', day)
    .replace('YYYY', year)
    .replace('YY', String(year).slice(-2))
    .replace('HH', hours)
    .replace('mm', minutes);
};

// Get time from date
export const getTime = (date) => {
  if (!date) return '';
  const d = new Date(date);
  const hours = d.getHours();
  const minutes = d.getMinutes().toString().padStart(2, '0');
  const ampm = hours >= 12 ? 'PM' : 'AM';
  const hour12 = hours % 12 || 12;
  return `${hour12}:${minutes} ${ampm}`;
};

// Check if event is upcoming
export const isUpcoming = (date) => {
  return new Date(date) > new Date();
};

// Check if event is ongoing
export const isOngoing = (startDate, endDate) => {
  const now = new Date();
  const start = new Date(startDate);
  const end = endDate ? new Date(endDate) : null;
  return start <= now && (!end || end >= now);
};

// Get relative time (e.g., "2 days ago", "in 3 days")
export const getRelativeTime = (date) => {
  const now = new Date();
  const target = new Date(date);
  const diff = target - now;
  const days = Math.floor(Math.abs(diff) / (1000 * 60 * 60 * 24));
  const hours = Math.floor(Math.abs(diff) / (1000 * 60 * 60));
  const minutes = Math.floor(Math.abs(diff) / (1000 * 60));
  
  if (diff < 0) {
    if (days > 0) return `${days} day${days > 1 ? 's' : ''} ago`;
    if (hours > 0) return `${hours} hour${hours > 1 ? 's' : ''} ago`;
    return `${minutes} minute${minutes > 1 ? 's' : ''} ago`;
  } else {
    if (days > 0) return `in ${days} day${days > 1 ? 's' : ''}`;
    if (hours > 0) return `in ${hours} hour${hours > 1 ? 's' : ''}`;
    return `in ${minutes} minute${minutes > 1 ? 's' : ''}`;
  }
};

// Get status color
export const getStatusColor = (status) => {
  const colors = {
    upcoming: 'blue',
    ongoing: 'green',
    completed: 'gray',
    cancelled: 'red'
  };
  return colors[status] || 'gray';
};

// Get status label
export const getStatusLabel = (status) => {
  const labels = {
    upcoming: 'Upcoming',
    ongoing: 'Ongoing',
    completed: 'Completed',
    cancelled: 'Cancelled'
  };
  return labels[status] || 'Unknown';
};

// Get type icon
export const getTypeIcon = (type) => {
  const icons = {
    conference: '📚',
    workshop: '🔧',
    seminar: '🎤',
    summit: '🏔️',
    training: '💡',
    social: '🎉',
    other: '📌'
  };
  return icons[type] || '📌';
};

// Get type label
export const getTypeLabel = (type) => {
  const labels = {
    conference: 'Conference',
    workshop: 'Workshop',
    seminar: 'Seminar',
    summit: 'Summit',
    training: 'Training',
    social: 'Social',
    other: 'Other'
  };
  return labels[type] || 'Other';
};

// Format for calendar
export const getCalendarDays = (year, month) => {
  const firstDay = new Date(year, month - 1, 1);
  const lastDay = new Date(year, month, 0);
  const daysInMonth = lastDay.getDate();
  const startDayOfWeek = firstDay.getDay();
  
  const days = [];
  
  // Previous month days
  const prevMonthLastDay = new Date(year, month - 1, 0).getDate();
  for (let i = startDayOfWeek - 1; i >= 0; i--) {
    days.push({
      date: new Date(year, month - 2, prevMonthLastDay - i),
      isCurrentMonth: false
    });
  }
  
  // Current month days
  for (let i = 1; i <= daysInMonth; i++) {
    days.push({
      date: new Date(year, month - 1, i),
      isCurrentMonth: true
    });
  }
  
  // Next month days
  const remainingDays = 42 - days.length;
  for (let i = 1; i <= remainingDays; i++) {
    days.push({
      date: new Date(year, month, i),
      isCurrentMonth: false
    });
  }
  
  return days;
};