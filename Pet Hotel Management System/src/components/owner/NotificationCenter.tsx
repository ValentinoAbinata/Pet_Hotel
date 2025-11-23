import { Card, CardContent, CardHeader, CardTitle } from '../ui/card';
import { Badge } from '../ui/badge';
import { Bell, Utensils, Scissors, Syringe, Calendar, Check } from 'lucide-react';
import { Button } from '../ui/button';

const notifications = [
  {
    id: '1',
    type: 'feeding',
    title: 'Waktu Makan Malam',
    message: 'Luna akan diberi makan malam pada pukul 18:00',
    time: '17:30',
    icon: Utensils,
    color: 'orange',
    read: false,
  },
  {
    id: '2',
    type: 'grooming',
    title: 'Jadwal Grooming',
    message: 'Luna dijadwalkan grooming besok pukul 10:00',
    time: '15:00',
    icon: Scissors,
    color: 'blue',
    read: false,
  },
  {
    id: '3',
    type: 'vaccine',
    title: 'Pengingat Vaksinasi',
    message: 'Vaksinasi Luna dijadwalkan pada 11 Nov 2025 pukul 14:00',
    time: '14:00',
    icon: Syringe,
    color: 'red',
    read: false,
  },
  {
    id: '4',
    type: 'feeding',
    title: 'Makan Siang Selesai',
    message: 'Luna telah selesai makan siang (80% dimakan)',
    time: '12:15',
    icon: Utensils,
    color: 'green',
    read: true,
  },
  {
    id: '5',
    type: 'pickup',
    title: 'Pengingat Check-out',
    message: 'Luna dijadwalkan check-out pada 12 Nov 2025',
    time: '10:00',
    icon: Calendar,
    color: 'purple',
    read: true,
  },
];

const iconColorMap = {
  orange: 'bg-orange-100 text-orange-600',
  blue: 'bg-blue-100 text-blue-600',
  red: 'bg-red-100 text-red-600',
  green: 'bg-green-100 text-green-600',
  purple: 'bg-purple-100 text-purple-600',
};

export function NotificationCenter() {
  const unreadCount = notifications.filter(n => !n.read).length;

  return (
    <div className="p-4 lg:p-8 space-y-6">
      <div>
        <h1 className="text-2xl lg:text-3xl mb-2">Pusat Notifikasi</h1>
        <p className="text-gray-600">
          Tetap update dengan semua informasi tentang hewan peliharaan Anda
        </p>
      </div>

      {/* Summary Card */}
      <Card className="bg-gradient-to-br from-blue-50 to-purple-50">
        <CardContent className="pt-6">
          <div className="flex items-center justify-between">
            <div className="flex items-center gap-4">
              <div className="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center">
                <Bell className="w-6 h-6 text-white" />
              </div>
              <div>
                <h3 className="text-lg">Notifikasi Belum Dibaca</h3>
                <p className="text-2xl text-blue-600">{unreadCount}</p>
              </div>
            </div>
            <Button variant="outline" size="sm">
              Tandai Semua Dibaca
            </Button>
          </div>
        </CardContent>
      </Card>

      {/* Notifications List */}
      <Card>
        <CardHeader>
          <CardTitle>Semua Notifikasi</CardTitle>
        </CardHeader>
        <CardContent className="space-y-3">
          {notifications.map((notification) => {
            const Icon = notification.icon;
            const colorClass = iconColorMap[notification.color as keyof typeof iconColorMap];
            
            return (
              <div
                key={notification.id}
                className={`p-4 rounded-xl border-2 transition-all ${
                  notification.read
                    ? 'bg-white border-gray-100'
                    : 'bg-blue-50 border-blue-200'
                }`}
              >
                <div className="flex gap-4">
                  <div className={`w-12 h-12 rounded-full flex items-center justify-center flex-shrink-0 ${colorClass}`}>
                    <Icon className="w-6 h-6" />
                  </div>
                  <div className="flex-1 min-w-0">
                    <div className="flex items-start justify-between gap-2 mb-1">
                      <h4 className="truncate">{notification.title}</h4>
                      <div className="flex items-center gap-2 flex-shrink-0">
                        <span className="text-sm text-gray-600">{notification.time}</span>
                        {!notification.read && (
                          <div className="w-2 h-2 bg-blue-600 rounded-full" />
                        )}
                      </div>
                    </div>
                    <p className="text-sm text-gray-600">{notification.message}</p>
                    {!notification.read && (
                      <Button variant="ghost" size="sm" className="mt-2 h-8 px-3">
                        <Check className="w-4 h-4 mr-1" />
                        Tandai Dibaca
                      </Button>
                    )}
                  </div>
                </div>
              </div>
            );
          })}
        </CardContent>
      </Card>

      {/* Notification Settings */}
      <Card>
        <CardHeader>
          <CardTitle>Pengaturan Notifikasi</CardTitle>
        </CardHeader>
        <CardContent className="space-y-4">
          {[
            { label: 'Notifikasi Makan', enabled: true },
            { label: 'Notifikasi Grooming', enabled: true },
            { label: 'Notifikasi Kesehatan', enabled: true },
            { label: 'Pengingat Check-out', enabled: true },
            { label: 'Update Aktivitas Harian', enabled: false },
          ].map((setting, index) => (
            <div key={index} className="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
              <span>{setting.label}</span>
              <div
                className={`w-12 h-6 rounded-full transition-colors cursor-pointer ${
                  setting.enabled ? 'bg-blue-600' : 'bg-gray-300'
                }`}
              >
                <div
                  className={`w-5 h-5 bg-white rounded-full mt-0.5 transition-transform ${
                    setting.enabled ? 'ml-6' : 'ml-0.5'
                  }`}
                />
              </div>
            </div>
          ))}
        </CardContent>
      </Card>
    </div>
  );
}
