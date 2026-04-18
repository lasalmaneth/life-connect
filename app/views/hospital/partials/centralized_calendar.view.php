<div class="calendar-container premium-card">
    <div class="calendar-header">
        <div class="calendar-nav">
            <button id="prevMonth" class="btn-icon"><i class="fas fa-chevron-left"></i></button>
            <h3 id="currentMonthYear">Month Year</h3>
            <button id="nextMonth" class="btn-icon"><i class="fas fa-chevron-right"></i></button>
        </div>
        <div class="calendar-legend">
            <div class="legend-item"><span class="dot aftercare-recipient"></span> Recipient Aftercare</div>
            <div class="legend-item"><span class="dot support-donor"></span> Donor Support</div>
            <div class="legend-item"><span class="dot test-donor"></span> Donor Tests</div>
            <div class="legend-item"><span class="dot surgery"></span> Surgeries</div>
        </div>
    </div>
    <div class="calendar-grid-wrapper">
        <div class="calendar-weekdays">
            <div>Sun</div><div>Mon</div><div>Tue</div><div>Wed</div><div>Thu</div><div>Fri</div><div>Sat</div>
        </div>
        <div id="calendarDays" class="calendar-days"></div>
    </div>
</div>

<div id="calendar-event-modal" class="modal">
    <div class="modal-content calendar-modal">
        <div class="modal-header">
            <h3 id="calModalTitle">Event Details</h3>
            <span class="close-modal" onclick="closeCalendarModal()">&times;</span>
        </div>
        <div class="modal-body" id="calModalBody">
            <!-- Details loaded here -->
        </div>
    </div>
</div>

<style>
.calendar-container {
    background: #fff;
    border-radius: 16px;
    padding: 1.25rem;
    box-shadow: 0 10px 30px rgba(0,0,0,0.05);
    margin-bottom: 2rem;
}
.calendar-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    flex-wrap: wrap;
    gap: 1rem;
}
.calendar-nav {
    display: flex;
    align-items: center;
    gap: 1.5rem;
}
.calendar-nav h3 {
    margin: 0;
    font-size: 1.5rem;
    font-weight: 700;
    color: #1e293b;
    min-width: 200px;
    text-align: center;
}
.btn-icon {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s;
    color: #64748b;
}
.btn-icon:hover {
    background: #f1f5f9;
    color: #2563eb;
    border-color: #2563eb;
}
.calendar-legend {
    display: flex;
    gap: 1.5rem;
    flex-wrap: wrap;
}
.legend-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.85rem;
    color: #64748b;
    font-weight: 500;
}
.dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
}
.dot.aftercare-recipient { background: #0ea5e9; } 
.dot.support-donor { background: #22c55e; } 
.dot.test-donor { background: #f59e0b; } 
.dot.surgery { background: #ef4444; } 

.calendar-weekdays {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    text-align: center;
    font-weight: 600;
    color: #94a3b8;
    text-transform: uppercase;
    font-size: 0.75rem;
    letter-spacing: 0.05em;
    padding-bottom: 1rem;
    border-bottom: 1px solid #f1f5f9;
}
.calendar-days {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    margin-top: 1rem;
    border-top: 1px solid #e2e8f0;
    border-left: 1px solid #e2e8f0;
    border-radius: 8px;
    overflow: hidden;
}
.calendar-day {
    height: 140px;
    padding: 0.5rem;
    border-right: 1px solid #e2e8f0;
    border-bottom: 1px solid #e2e8f0;
    position: relative;
    transition: all 0.2s;
    display: flex;
    flex-direction: column;
}
.calendar-day:hover {
    background: #f8fafc;
}
.calendar-day.today {
    background: #f0f7ff;
}
.calendar-day.today .day-number {
    background: #2563eb;
    color: #fff;
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 6px;
}
.day-number {
    font-size: 0.9rem;
    font-weight: 600;
    color: #64748b;
    margin-bottom: 0.5rem;
}
.calendar-day.not-current {
    opacity: 0.3;
}
.event-indicators {
    display: flex;
    flex-direction: column;
    gap: 0.35rem;
    flex: 1;
    overflow-y: auto;
    padding-right: 2px;
}
/* Custom Scrollbar for event indicators */
.event-indicators::-webkit-scrollbar {
    width: 3px;
}
.event-indicators::-webkit-scrollbar-track {
    background: transparent;
}
.event-indicators::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 10px;
}
.event-item {
    font-size: 0.65rem;
    padding: 0.3rem 0.6rem;
    border-radius: 6px;
    color: #fff;
    cursor: pointer;
    white-space: normal;
    word-break: break-word;
    font-weight: 500;
    line-height: 1.2;
    transition: transform 0.1s, filter 0.2s;
}
.event-item:hover {
    transform: scale(1.02);
    filter: brightness(1.1);
}
.event-item.aftercare-recipient { background: #e0f2fe; color: #0369a1; border-left: 3px solid #0ea5e9; }
.event-item.support-donor { background: #dcfce7; color: #15803d; border-left: 3px solid #22c55e; }
.event-item.test-donor { background: #fef3c7; color: #b45309; border-left: 3px solid #f59e0b; }
.event-item.surgery { background: #fee2e2; color: #b91c1c; border-left: 3px solid #ef4444; }

.calendar-modal {
    max-width: 500px;
}
.event-detail-row {
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 1px solid #f1f5f9;
}
.event-detail-row:last-child { border-bottom: none; }
.event-label {
    font-size: 0.75rem;
    color: #94a3b8;
    text-transform: uppercase;
    font-weight: 600;
    margin-bottom: 0.25rem;
}
.event-value {
    font-size: 1rem;
    color: #1e293b;
    font-weight: 500;
}
.badge-type {
    display: inline-block;
    padding: 0.25rem 0.5rem;
    border-radius: 6px;
    font-size: 0.75rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}
.badge-type.aftercare-recipient { background: #dbeafe; color: #1e40af; }
.badge-type.support-donor { background: #d1fae5; color: #065f46; }
.badge-type.test-donor { background: #fef3c7; color: #92400e; }
.badge-type.surgery { background: #fee2e2; color: #991b1b; }
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const calendarDays = document.getElementById('calendarDays');
    const currentMonthYear = document.getElementById('currentMonthYear');
    const prevMonthBtn = document.getElementById('prevMonth');
    const nextMonthBtn = document.getElementById('nextMonth');

    let currentDate = new Date();

    // Data from PHP
    const events = {
        recipientAftercare: <?php echo json_encode($aftercare_appointments ?? []); ?>,
        donorSupport: <?php echo json_encode($aftercare_support_requests ?? []); ?>,
        donorTests: <?php echo json_encode($lab_reports ?? []); ?>,
        surgeries: <?php echo json_encode($surgery_matches ?? []); ?>
    };

    function renderCalendar() {
        calendarDays.innerHTML = '';
        const year = currentDate.getFullYear();
        const month = currentDate.getMonth();

        currentMonthYear.textContent = new Intl.DateTimeFormat('en-US', { month: 'long', year: 'numeric' }).format(currentDate);

        const firstDay = new Date(year, month, 1).getDay();
        const lastDay = new Date(year, month + 1, 0).getDate();
        const prevLastDay = new Date(year, month, 0).getDate();

        // Previous month days
        for (let x = firstDay; x > 0; x--) {
            const dayDiv = createDayElement(prevLastDay - x + 1, false, true);
            calendarDays.appendChild(dayDiv);
        }

        // Current month days
        const today = new Date();
        for (let i = 1; i <= lastDay; i++) {
            const isToday = i === today.getDate() && month === today.getMonth() && year === today.getFullYear();
            const dayDiv = createDayElement(i, isToday, false);
            
            // Add events for this day
            const dayEvents = getEventsForDate(new Date(year, month, i));
            const indicators = dayDiv.querySelector('.event-indicators');
            
            dayEvents.forEach(ev => {
                const evDiv = document.createElement('div');
                evDiv.className = `event-item ${ev.category}`;
                evDiv.textContent = ev.title;
                evDiv.onclick = (e) => {
                    e.stopPropagation();
                    showEventDetails(ev);
                };
                indicators.appendChild(evDiv);
            });

            calendarDays.appendChild(dayDiv);
        }

        // Next month days
        const totalSlots = 42; 
        const nextDays = totalSlots - (firstDay + lastDay);
        for (let j = 1; j <= nextDays; j++) {
            const dayDiv = createDayElement(j, false, true);
            calendarDays.appendChild(dayDiv);
        }
    }

    function createDayElement(day, isToday, isNotCurrent) {
        const div = document.createElement('div');
        div.className = `calendar-day ${isToday ? 'today' : ''} ${isNotCurrent ? 'not-current' : ''}`;
        div.innerHTML = `<div class="day-number">${day}</div><div class="event-indicators"></div>`;
        return div;
    }

    function getEventsForDate(date) {
        const dateStr = date.toISOString().split('T')[0];
        const dayEvents = [];

        // 1. Recipient Aftercare
        events.recipientAftercare.forEach(ev => {
            const evDate = ev.appointment_date ? ev.appointment_date.split(' ')[0] : '';
            if (evDate === dateStr) {
                dayEvents.push({
                    title: ev.appointment_type || 'Aftercare',
                    category: 'aftercare-recipient',
                    data: ev,
                    type: 'Recipient Appointment'
                });
            }
        });

        // 2. Donor Support
        events.donorSupport.forEach(ev => {
            const evDate = ev.submitted_date || (ev.created_at ? ev.created_at.split(' ')[0] : '');
            if (evDate === dateStr) {
                dayEvents.push({
                    title: ev.reason || 'Support Request',
                    category: 'support-donor',
                    data: ev,
                    type: 'Donor Support Request'
                });
            }
        });

        // 3. Donor Tests
        events.donorTests.forEach(ev => {
            const evDate = ev.test_date || ev.scheduled_date_1 || '';
            if (evDate === dateStr) {
                dayEvents.push({
                    title: ev.test_type || 'Lab Test',
                    category: 'test-donor',
                    data: ev,
                    type: 'Donor Test Appointment'
                });
            }
        });

        // 4. Surgeries
        events.surgeries.forEach(ev => {
            const evDate = ev.surgery_date ? ev.surgery_date.split(' ')[0] : '';
            if (evDate === dateStr) {
                dayEvents.push({
                    title: 'Surgery: ' + (ev.organ_name || 'Organ'),
                    category: 'surgery',
                    data: ev,
                    type: 'Scheduled Surgery'
                });
            }
        });

        return dayEvents;
    }

    function showEventDetails(ev) {
        const modal = document.getElementById('calendar-event-modal');
        const title = document.getElementById('calModalTitle');
        const body = document.getElementById('calModalBody');

        title.textContent = 'Event Details';
        let html = `<div class="badge-type ${ev.category}">${ev.type}</div>`;
        
        if (ev.category === 'aftercare-recipient') {
            html += `
                <div class="event-detail-row"><div class="event-label">Patient</div><div class="event-value">${ev.data.patient_name || 'N/A'}</div></div>
                <div class="event-detail-row"><div class="event-label">NIC</div><div class="event-value">${ev.data.patient_id || 'N/A'}</div></div>
                <div class="event-detail-row"><div class="event-label">Appointment</div><div class="event-value">${ev.data.appointment_type}</div></div>
                <div class="event-detail-row"><div class="event-label">Details</div><div class="event-value">${ev.data.description || 'No additional details.'}</div></div>
                <div class="event-detail-row"><div class="event-label">Status</div><div class="event-value">${ev.data.status}</div></div>
            `;
        } else if (ev.category === 'support-donor') {
            html += `
                <div class="event-detail-row"><div class="event-label">Donor</div><div class="event-value">${ev.data.patient_name || 'N/A'}</div></div>
                <div class="event-detail-row"><div class="event-label">NIC</div><div class="event-value">${ev.data.patient_nic || 'N/A'}</div></div>
                <div class="event-detail-row"><div class="event-label">Reason</div><div class="event-value">${ev.data.reason}</div></div>
                <div class="event-detail-row"><div class="event-label">Amount Requested</div><div class="event-value">${ev.data.amount ? 'Rs. ' + ev.data.amount : 'N/A'}</div></div>
                <div class="event-detail-row"><div class="event-label">Status</div><div class="event-value">${ev.data.status}</div></div>
            `;
        } else if (ev.category === 'test-donor') {
            html += `
                <div class="event-detail-row"><div class="event-label">Donor</div><div class="event-value">${ev.data.donor_name || 'N/A'}</div></div>
                <div class="event-detail-row"><div class="event-label">NIC</div><div class="event-value">${ev.data.donor_nic || 'N/A'}</div></div>
                <div class="event-detail-row"><div class="event-label">Test Type</div><div class="event-value">${ev.data.test_type}</div></div>
                <div class="event-detail-row"><div class="event-label">Status</div><div class="event-value">${ev.data.status}</div></div>
            `;
        } else if (ev.category === 'surgery') {
            html += `
                <div class="event-detail-row"><div class="event-label">Donor</div><div class="event-value">${ev.data.donor_first_name} ${ev.data.donor_last_name}</div></div>
                <div class="event-detail-row"><div class="event-label">Organ</div><div class="event-value">${ev.data.organ_name}</div></div>
                <div class="event-detail-row"><div class="event-label">Surgery Date</div><div class="event-value">${ev.data.surgery_date}</div></div>
                <div class="event-detail-row"><div class="event-label">Match Status</div><div class="event-value">${ev.data.hospital_match_status || 'PENDING'}</div></div>
            `;
        }

        body.innerHTML = html;
        modal.classList.add('show');
    }

    prevMonthBtn.onclick = () => {
        currentDate.setMonth(currentDate.getMonth() - 1);
        renderCalendar();
    };

    nextMonthBtn.onclick = () => {
        currentDate.setMonth(currentDate.getMonth() + 1);
        renderCalendar();
    };

    renderCalendar();
});

function closeCalendarModal() {
    document.getElementById('calendar-event-modal').classList.remove('show');
}
</script>
