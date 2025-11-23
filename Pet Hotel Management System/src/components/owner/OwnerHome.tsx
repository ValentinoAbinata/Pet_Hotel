import { Card, CardContent, CardHeader, CardTitle } from '../ui/card';
import { Button } from '../ui/button';
import { Calendar, Activity, Bell, Heart, MapPin, Phone } from 'lucide-react';
import { Badge } from '../ui/badge';

const myPets = [
  {
    id: '1',
    name: 'Luna',
    type: 'Kucing Persian',
    status: 'Menginap',
    checkIn: '2025-11-08',
    checkOut: '2025-11-12',
    room: 'Suite A-101',
    image: 'https://images.unsplash.com/photo-1573865526739-10c1d3a1f0cc?w=400'
  },
];

const upcomingSchedules = [
  { id: '1', pet: 'Luna', activity: 'Grooming', time: '10:00', date: '2025-11-10' },
  { id: '2', pet: 'Luna', activity: 'Vaksinasi', time: '14:00', date: '2025-11-11' },
];

export function OwnerHome() {
  return (
    <div className="p-4 lg:p-8 space-y-6">
      {/* Welcome Section */}
      <div className="bg-gradient-to-r from-blue-500 to-green-500 rounded-2xl p-6 lg:p-8 text-white">
        <h2 className="text-2xl lg:text-3xl mb-2">Selamat Datang!</h2>
        <p className="text-blue-50">
          Pantau dan kelola penitipan hewan peliharaan Anda dengan mudah
        </p>
      </div>

      {/* Quick Stats */}
      <div className="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <Card>
          <CardContent className="pt-6">
            <div className="text-center">
              <div className="mx-auto w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mb-2">
                <Heart className="w-6 h-6 text-blue-600" />
              </div>
              <div className="text-2xl">1</div>
              <div className="text-sm text-gray-600">Hewan Aktif</div>
            </div>
          </CardContent>
        </Card>

        <Card>
          <CardContent className="pt-6">
            <div className="text-center">
              <div className="mx-auto w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mb-2">
                <Calendar className="w-6 h-6 text-green-600" />
              </div>
              <div className="text-2xl">2</div>
              <div className="text-sm text-gray-600">Jadwal</div>
            </div>
          </CardContent>
        </Card>

        <Card>
          <CardContent className="pt-6">
            <div className="text-center">
              <div className="mx-auto w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center mb-2">
                <Bell className="w-6 h-6 text-orange-600" />
              </div>
              <div className="text-2xl">3</div>
              <div className="text-sm text-gray-600">Notifikasi</div>
            </div>
          </CardContent>
        </Card>

        <Card>
          <CardContent className="pt-6">
            <div className="text-center">
              <div className="mx-auto w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mb-2">
                <Activity className="w-6 h-6 text-purple-600" />
              </div>
              <div className="text-2xl">98%</div>
              <div className="text-sm text-gray-600">Kesehatan</div>
            </div>
          </CardContent>
        </Card>
      </div>

      {/* Current Pets */}
      <Card>
        <CardHeader>
          <CardTitle>Hewan Peliharaan Saya</CardTitle>
        </CardHeader>
        <CardContent className="space-y-4">
          {myPets.map((pet) => (
            <div key={pet.id} className="flex flex-col lg:flex-row gap-4 p-4 bg-gray-50 rounded-xl">
              <img
                src={pet.image}
                alt={pet.name}
                className="w-full lg:w-24 h-48 lg:h-24 object-cover rounded-lg"
              />
              <div className="flex-1 space-y-2">
                <div className="flex flex-wrap items-start justify-between gap-2">
                  <div>
                    <h3 className="text-lg">{pet.name}</h3>
                    <p className="text-sm text-gray-600">{pet.type}</p>
                  </div>
                  <Badge className="bg-green-100 text-green-700 hover:bg-green-100">
                    {pet.status}
                  </Badge>
                </div>
                <div className="grid grid-cols-1 lg:grid-cols-3 gap-2 text-sm">
                  <div className="flex items-center gap-2 text-gray-600">
                    <MapPin className="w-4 h-4" />
                    <span>{pet.room}</span>
                  </div>
                  <div className="flex items-center gap-2 text-gray-600">
                    <Calendar className="w-4 h-4" />
                    <span>Check-in: {pet.checkIn}</span>
                  </div>
                  <div className="flex items-center gap-2 text-gray-600">
                    <Calendar className="w-4 h-4" />
                    <span>Check-out: {pet.checkOut}</span>
                  </div>
                </div>
                <Button size="sm" className="w-full lg:w-auto">
                  Lihat Detail
                </Button>
              </div>
            </div>
          ))}
        </CardContent>
      </Card>

      {/* Upcoming Schedules */}
      <Card>
        <CardHeader>
          <CardTitle>Jadwal Mendatang</CardTitle>
        </CardHeader>
        <CardContent>
          <div className="space-y-3">
            {upcomingSchedules.map((schedule) => (
              <div key={schedule.id} className="flex items-center gap-4 p-3 bg-gray-50 rounded-lg">
                <div className="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                  <Activity className="w-6 h-6 text-blue-600" />
                </div>
                <div className="flex-1">
                  <div className="flex items-center gap-2">
                    <span>{schedule.activity}</span>
                    <span className="text-sm text-gray-600">- {schedule.pet}</span>
                  </div>
                  <p className="text-sm text-gray-600">
                    {schedule.date} • {schedule.time}
                  </p>
                </div>
              </div>
            ))}
          </div>
        </CardContent>
      </Card>

      {/* Contact Info */}
      <Card className="bg-blue-50 border-blue-200">
        <CardContent className="pt-6">
          <div className="flex items-start gap-4">
            <div className="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center">
              <Phone className="w-6 h-6 text-white" />
            </div>
            <div>
              <h3 className="mb-1">Butuh Bantuan?</h3>
              <p className="text-sm text-gray-600 mb-2">
                Hubungi kami kapan saja untuk informasi tentang hewan peliharaan Anda
              </p>
              <Button variant="outline" size="sm">
                Hubungi Pet Hotel
              </Button>
            </div>
          </div>
        </CardContent>
      </Card>
    </div>
  );
}
