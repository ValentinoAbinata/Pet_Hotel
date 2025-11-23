import { useState } from 'react';
import { Card, CardContent, CardHeader, CardTitle } from '../ui/card';
import { Button } from '../ui/button';
import { Badge } from '../ui/badge';
import { ChevronLeft, ChevronRight, Calendar as CalendarIcon } from 'lucide-react';

const mockEvents = [
  { id: '1', date: '2025-11-10', pet: 'Luna', type: 'Check-in', time: '09:00' },
  { id: '2', date: '2025-11-10', pet: 'Max', type: 'Grooming', time: '14:00' },
  { id: '3', date: '2025-11-11', pet: 'Luna', type: 'Vaksinasi', time: '14:00' },
  { id: '4', date: '2025-11-12', pet: 'Luna', type: 'Check-out', time: '10:00' },
  { id: '5', date: '2025-11-12', pet: 'Bella', type: 'Check-in', time: '11:00' },
  { id: '6', date: '2025-11-13', pet: 'Max', type: 'Cek Kesehatan', time: '10:30' },
  { id: '7', date: '2025-11-15', pet: 'Charlie', type: 'Check-in', time: '09:00' },
];

const typeColors: Record<string, string> = {
  'Check-in': 'bg-green-100 text-green-700 border-green-200',
  'Check-out': 'bg-blue-100 text-blue-700 border-blue-200',
  'Grooming': 'bg-purple-100 text-purple-700 border-purple-200',
  'Vaksinasi': 'bg-red-100 text-red-700 border-red-200',
  'Cek Kesehatan': 'bg-orange-100 text-orange-700 border-orange-200',
};

export function ScheduleCalendar() {
  const [currentDate, setCurrentDate] = useState(new Date(2025, 10, 10)); // Nov 10, 2025
  const [selectedDate, setSelectedDate] = useState('2025-11-10');

  const getDaysInMonth = (date: Date) => {
    const year = date.getFullYear();
    const month = date.getMonth();
    const firstDay = new Date(year, month, 1);
    const lastDay = new Date(year, month + 1, 0);
    const daysInMonth = lastDay.getDate();
    const startingDayOfWeek = firstDay.getDay();

    return { daysInMonth, startingDayOfWeek };
  };

  const { daysInMonth, startingDayOfWeek } = getDaysInMonth(currentDate);

  const previousMonth = () => {
    setCurrentDate(new Date(currentDate.getFullYear(), currentDate.getMonth() - 1));
  };

  const nextMonth = () => {
    setCurrentDate(new Date(currentDate.getFullYear(), currentDate.getMonth() + 1));
  };

  const formatDate = (day: number) => {
    const year = currentDate.getFullYear();
    const month = String(currentDate.getMonth() + 1).padStart(2, '0');
    const dayStr = String(day).padStart(2, '0');
    return `${year}-${month}-${dayStr}`;
  };

  const getEventsForDate = (date: string) => {
    return mockEvents.filter(event => event.date === date);
  };

  const selectedEvents = getEventsForDate(selectedDate);

  return (
    <div className="p-4 lg:p-8 space-y-6">
      <div>
        <h1 className="text-2xl lg:text-3xl mb-2">Kalender Jadwal</h1>
        <p className="text-gray-600">Kelola dan visualisasi semua reservasi dan jadwal perawatan</p>
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {/* Calendar */}
        <Card className="lg:col-span-2">
          <CardHeader>
            <div className="flex items-center justify-between">
              <CardTitle>
                {currentDate.toLocaleDateString('id-ID', { month: 'long', year: 'numeric' })}
              </CardTitle>
              <div className="flex gap-2">
                <Button variant="outline" size="sm" onClick={previousMonth}>
                  <ChevronLeft className="w-4 h-4" />
                </Button>
                <Button variant="outline" size="sm" onClick={nextMonth}>
                  <ChevronRight className="w-4 h-4" />
                </Button>
              </div>
            </div>
          </CardHeader>
          <CardContent>
            <div className="grid grid-cols-7 gap-1 mb-2">
              {['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'].map((day) => (
                <div key={day} className="text-center text-sm text-gray-600 py-2">
                  {day}
                </div>
              ))}
            </div>
            <div className="grid grid-cols-7 gap-1">
              {Array.from({ length: startingDayOfWeek }).map((_, i) => (
                <div key={`empty-${i}`} className="aspect-square" />
              ))}
              {Array.from({ length: daysInMonth }).map((_, i) => {
                const day = i + 1;
                const dateStr = formatDate(day);
                const hasEvents = mockEvents.some(event => event.date === dateStr);
                const isSelected = dateStr === selectedDate;

                return (
                  <button
                    key={day}
                    onClick={() => setSelectedDate(dateStr)}
                    className={`aspect-square p-2 rounded-lg border transition-all ${
                      isSelected
                        ? 'bg-blue-600 text-white border-blue-600'
                        : hasEvents
                        ? 'bg-blue-50 border-blue-200 text-blue-600 hover:bg-blue-100'
                        : 'border-gray-200 hover:bg-gray-50'
                    }`}
                  >
                    <div className="text-sm">{day}</div>
                    {hasEvents && !isSelected && (
                      <div className="w-1 h-1 bg-blue-600 rounded-full mx-auto mt-1" />
                    )}
                  </button>
                );
              })}
            </div>
          </CardContent>
        </Card>

        {/* Events for Selected Date */}
        <Card>
          <CardHeader>
            <CardTitle className="flex items-center gap-2">
              <CalendarIcon className="w-5 h-5" />
              Jadwal
            </CardTitle>
            <p className="text-sm text-gray-600">
              {new Date(selectedDate).toLocaleDateString('id-ID', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric',
              })}
            </p>
          </CardHeader>
          <CardContent>
            {selectedEvents.length === 0 ? (
              <div className="text-center py-8 text-gray-500">
                Tidak ada jadwal untuk tanggal ini
              </div>
            ) : (
              <div className="space-y-3">
                {selectedEvents.map((event) => (
                  <div key={event.id} className="p-3 bg-gray-50 rounded-lg">
                    <div className="flex items-start justify-between mb-2">
                      <div>
                        <h4>{event.pet}</h4>
                        <p className="text-sm text-gray-600">{event.time}</p>
                      </div>
                    </div>
                    <Badge variant="outline" className={typeColors[event.type]}>
                      {event.type}
                    </Badge>
                  </div>
                ))}
              </div>
            )}
          </CardContent>
        </Card>
      </div>

      {/* Upcoming Events */}
      <Card>
        <CardHeader>
          <CardTitle>Jadwal Mendatang</CardTitle>
        </CardHeader>
        <CardContent>
          <div className="space-y-3">
            {mockEvents.slice(0, 5).map((event) => (
              <div key={event.id} className="flex items-center gap-4 p-3 bg-gray-50 rounded-lg">
                <div className="flex-shrink-0 text-center">
                  <div className="text-sm text-gray-600">
                    {new Date(event.date).toLocaleDateString('id-ID', { month: 'short' })}
                  </div>
                  <div className="text-xl">
                    {new Date(event.date).getDate()}
                  </div>
                </div>
                <div className="flex-1">
                  <div className="flex items-center gap-2 mb-1">
                    <span>{event.pet}</span>
                    <span className="text-sm text-gray-600">• {event.time}</span>
                  </div>
                  <Badge variant="outline" className={typeColors[event.type]}>
                    {event.type}
                  </Badge>
                </div>
              </div>
            ))}
          </div>
        </CardContent>
      </Card>
    </div>
  );
}
