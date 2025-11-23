import { Card, CardContent, CardHeader, CardTitle } from '../ui/card';
import { Badge } from '../ui/badge';
import { Activity, Heart, Utensils, Thermometer, Camera, Clock } from 'lucide-react';
import { Progress } from '../ui/progress';

const petData = {
  name: 'Luna',
  type: 'Kucing Persian',
  status: 'Sehat',
  lastUpdate: '2025-11-10 14:30',
  photo: 'https://images.unsplash.com/photo-1573865526739-10c1d3a1f0cc?w=600',
  health: {
    temperature: 38.5,
    heartRate: 140,
    status: 'Normal',
  },
  feeding: [
    { time: '07:00', amount: '100g', eaten: 100, notes: 'Habis semua' },
    { time: '12:00', amount: '100g', eaten: 80, notes: 'Sisa sedikit' },
    { time: '18:00', amount: '100g', eaten: 0, notes: 'Belum waktunya' },
  ],
  activities: [
    { time: '08:00', activity: 'Bermain', duration: '30 menit', notes: 'Aktif dan ceria' },
    { time: '10:00', activity: 'Istirahat', duration: '2 jam', notes: 'Tidur nyenyak' },
    { time: '13:00', activity: 'Grooming', duration: '45 menit', notes: 'Bulu disisir' },
    { time: '15:00', activity: 'Bermain', duration: '20 menit', notes: 'Main dengan mainan baru' },
  ],
};

export function PetMonitoring() {
  return (
    <div className="p-4 lg:p-8 space-y-6">
      <div>
        <h1 className="text-2xl lg:text-3xl mb-2">Monitoring Real-time</h1>
        <p className="text-gray-600">
          Pantau kondisi dan aktivitas hewan peliharaan Anda secara real-time
        </p>
      </div>

      {/* Pet Profile Card */}
      <Card className="overflow-hidden">
        <div className="relative h-48 lg:h-64">
          <img
            src={petData.photo}
            alt={petData.name}
            className="w-full h-full object-cover"
          />
          <div className="absolute top-4 right-4">
            <Badge className="bg-green-500 hover:bg-green-500">
              Live
            </Badge>
          </div>
        </div>
        <CardContent className="pt-6">
          <div className="flex items-start justify-between mb-4">
            <div>
              <h2 className="text-2xl mb-1">{petData.name}</h2>
              <p className="text-gray-600">{petData.type}</p>
            </div>
            <Badge className="bg-blue-100 text-blue-700 hover:bg-blue-100">
              {petData.status}
            </Badge>
          </div>
          <div className="flex items-center gap-2 text-sm text-gray-600">
            <Clock className="w-4 h-4" />
            <span>Update terakhir: {petData.lastUpdate}</span>
          </div>
        </CardContent>
      </Card>

      {/* Health Status */}
      <Card>
        <CardHeader>
          <CardTitle className="flex items-center gap-2">
            <Heart className="w-5 h-5 text-red-500" />
            Status Kesehatan
          </CardTitle>
        </CardHeader>
        <CardContent>
          <div className="grid grid-cols-1 lg:grid-cols-3 gap-4">
            <div className="p-4 bg-gradient-to-br from-red-50 to-pink-50 rounded-xl">
              <div className="flex items-center gap-3 mb-2">
                <div className="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                  <Thermometer className="w-5 h-5 text-red-600" />
                </div>
                <div>
                  <p className="text-sm text-gray-600">Suhu Tubuh</p>
                  <p className="text-xl">{petData.health.temperature}°C</p>
                </div>
              </div>
              <p className="text-xs text-gray-600">Normal: 38.0-39.2°C</p>
            </div>

            <div className="p-4 bg-gradient-to-br from-blue-50 to-cyan-50 rounded-xl">
              <div className="flex items-center gap-3 mb-2">
                <div className="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                  <Heart className="w-5 h-5 text-blue-600" />
                </div>
                <div>
                  <p className="text-sm text-gray-600">Detak Jantung</p>
                  <p className="text-xl">{petData.health.heartRate} bpm</p>
                </div>
              </div>
              <p className="text-xs text-gray-600">Normal: 120-140 bpm</p>
            </div>

            <div className="p-4 bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl">
              <div className="flex items-center gap-3 mb-2">
                <div className="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                  <Activity className="w-5 h-5 text-green-600" />
                </div>
                <div>
                  <p className="text-sm text-gray-600">Kondisi Umum</p>
                  <p className="text-xl">{petData.health.status}</p>
                </div>
              </div>
              <p className="text-xs text-gray-600">Pemeriksaan terakhir hari ini</p>
            </div>
          </div>
        </CardContent>
      </Card>

      {/* Feeding Status */}
      <Card>
        <CardHeader>
          <CardTitle className="flex items-center gap-2">
            <Utensils className="w-5 h-5 text-orange-500" />
            Status Makan
          </CardTitle>
        </CardHeader>
        <CardContent className="space-y-4">
          {petData.feeding.map((meal, index) => (
            <div key={index} className="p-4 bg-gray-50 rounded-xl">
              <div className="flex items-start justify-between mb-3">
                <div>
                  <div className="flex items-center gap-2 mb-1">
                    <span className="text-sm text-gray-600">{meal.time}</span>
                    <Badge variant="outline" className="text-xs">
                      {meal.amount}
                    </Badge>
                  </div>
                  <p className="text-sm text-gray-600">{meal.notes}</p>
                </div>
                <div className="text-right">
                  <p className="text-sm">
                    {meal.eaten}% dimakan
                  </p>
                </div>
              </div>
              <Progress value={meal.eaten} className="h-2" />
            </div>
          ))}
        </CardContent>
      </Card>

      {/* Activities Timeline */}
      <Card>
        <CardHeader>
          <CardTitle className="flex items-center gap-2">
            <Activity className="w-5 h-5 text-purple-500" />
            Aktivitas Harian
          </CardTitle>
        </CardHeader>
        <CardContent>
          <div className="space-y-4">
            {petData.activities.map((activity, index) => (
              <div key={index} className="flex gap-4">
                <div className="flex flex-col items-center">
                  <div className="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                    <Activity className="w-5 h-5 text-purple-600" />
                  </div>
                  {index < petData.activities.length - 1 && (
                    <div className="w-0.5 h-12 bg-gray-200 my-1" />
                  )}
                </div>
                <div className="flex-1 pb-4">
                  <div className="flex items-center gap-2 mb-1">
                    <span className="text-sm text-gray-600">{activity.time}</span>
                    <span>•</span>
                    <span>{activity.activity}</span>
                  </div>
                  <p className="text-sm text-gray-600 mb-1">
                    Durasi: {activity.duration}
                  </p>
                  <p className="text-sm text-gray-500">{activity.notes}</p>
                </div>
              </div>
            ))}
          </div>
        </CardContent>
      </Card>

      {/* Live Camera Feed */}
      <Card>
        <CardHeader>
          <CardTitle className="flex items-center gap-2">
            <Camera className="w-5 h-5 text-blue-500" />
            Live Camera
          </CardTitle>
        </CardHeader>
        <CardContent>
          <div className="relative aspect-video bg-gray-900 rounded-lg overflow-hidden">
            <img
              src={petData.photo}
              alt="Live feed"
              className="w-full h-full object-cover opacity-90"
            />
            <div className="absolute top-4 left-4 flex items-center gap-2 bg-black/50 px-3 py-1.5 rounded-full">
              <div className="w-2 h-2 bg-red-500 rounded-full animate-pulse" />
              <span className="text-white text-sm">LIVE</span>
            </div>
            <div className="absolute bottom-4 left-4 right-4 text-white text-sm bg-black/50 px-3 py-2 rounded-lg">
              Suite A-101 • {new Date().toLocaleTimeString('id-ID')}
            </div>
          </div>
          <p className="text-sm text-gray-600 mt-3 text-center">
            Kamera diperbarui setiap 30 detik
          </p>
        </CardContent>
      </Card>
    </div>
  );
}
