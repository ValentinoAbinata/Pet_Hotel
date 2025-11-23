import { useState } from 'react';
import { Card, CardContent, CardHeader, CardTitle } from '../ui/card';
import { Button } from '../ui/button';
import { Input } from '../ui/input';
import { Label } from '../ui/label';
import { Textarea } from '../ui/textarea';
import { Badge } from '../ui/badge';
import { Heart, Thermometer, Syringe, FileText, Activity } from 'lucide-react';
import { ScheduleCalendar } from './ScheduleCalendar';
import { PetManagement } from './PetManagement';

interface VetDashboardProps {
  activeTab: string;
}

const appointments = [
  { id: '1', pet: 'Luna', type: 'Vaksinasi', time: '09:00', status: 'scheduled' },
  { id: '2', pet: 'Max', type: 'Cek Kesehatan', time: '10:30', status: 'in-progress' },
  { id: '3', pet: 'Bella', type: 'Konsultasi', time: '14:00', status: 'scheduled' },
];

export function VetDashboard({ activeTab }: VetDashboardProps) {
  const [showHealthForm, setShowHealthForm] = useState(false);
  const [healthData, setHealthData] = useState({
    pet: 'Max',
    temperature: '',
    heartRate: '',
    notes: '',
    diagnosis: '',
  });

  if (activeTab === 'schedule') {
    return <ScheduleCalendar />;
  }

  if (activeTab === 'pets') {
    return <PetManagement />;
  }

  const handleSubmitHealth = () => {
    alert('Data kesehatan berhasil disimpan dan notifikasi dikirim ke pemilik!');
    setShowHealthForm(false);
    setHealthData({ pet: 'Max', temperature: '', heartRate: '', notes: '', diagnosis: '' });
  };

  return (
    <div className="p-4 lg:p-8 space-y-6">
      <div>
        <h1 className="text-2xl lg:text-3xl mb-2">Dashboard Dokter Hewan</h1>
        <p className="text-gray-600">Kelola kesehatan dan perawatan medis hewan</p>
      </div>

      {/* Health Stats */}
      <div className="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <Card>
          <CardContent className="pt-6">
            <div className="flex items-center gap-3">
              <div className="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <Syringe className="w-6 h-6 text-blue-600" />
              </div>
              <div>
                <p className="text-sm text-gray-600">Vaksinasi</p>
                <p className="text-2xl">3</p>
              </div>
            </div>
          </CardContent>
        </Card>

        <Card>
          <CardContent className="pt-6">
            <div className="flex items-center gap-3">
              <div className="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                <Heart className="w-6 h-6 text-green-600" />
              </div>
              <div>
                <p className="text-sm text-gray-600">Cek Rutin</p>
                <p className="text-2xl">5</p>
              </div>
            </div>
          </CardContent>
        </Card>

        <Card>
          <CardContent className="pt-6">
            <div className="flex items-center gap-3">
              <div className="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                <Activity className="w-6 h-6 text-orange-600" />
              </div>
              <div>
                <p className="text-sm text-gray-600">Konsultasi</p>
                <p className="text-2xl">2</p>
              </div>
            </div>
          </CardContent>
        </Card>

        <Card>
          <CardContent className="pt-6">
            <div className="flex items-center gap-3">
              <div className="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                <Thermometer className="w-6 h-6 text-red-600" />
              </div>
              <div>
                <p className="text-sm text-gray-600">Perlu Perhatian</p>
                <p className="text-2xl">1</p>
              </div>
            </div>
          </CardContent>
        </Card>
      </div>

      {/* Quick Actions */}
      <Card>
        <CardHeader>
          <CardTitle>Aksi Cepat</CardTitle>
        </CardHeader>
        <CardContent>
          <div className="grid grid-cols-2 lg:grid-cols-4 gap-3">
            <Button 
              onClick={() => setShowHealthForm(true)}
              className="h-auto py-4 flex-col gap-2"
            >
              <Heart className="w-6 h-6" />
              <span>Input Kesehatan</span>
            </Button>
            <Button variant="outline" className="h-auto py-4 flex-col gap-2">
              <Syringe className="w-6 h-6" />
              <span>Jadwal Vaksin</span>
            </Button>
            <Button variant="outline" className="h-auto py-4 flex-col gap-2">
              <FileText className="w-6 h-6" />
              <span>Rekam Medis</span>
            </Button>
            <Button variant="outline" className="h-auto py-4 flex-col gap-2">
              <Thermometer className="w-6 h-6" />
              <span>Cek Vital</span>
            </Button>
          </div>
        </CardContent>
      </Card>

      {/* Health Form */}
      {showHealthForm && (
        <Card className="border-2 border-blue-500">
          <CardHeader>
            <CardTitle>Input Data Kesehatan</CardTitle>
          </CardHeader>
          <CardContent className="space-y-4">
            <div className="space-y-2">
              <Label htmlFor="pet">Hewan Peliharaan</Label>
              <select
                id="pet"
                className="w-full px-3 py-2 border rounded-lg"
                value={healthData.pet}
                onChange={(e) => setHealthData({ ...healthData, pet: e.target.value })}
              >
                <option value="Max">Max</option>
                <option value="Luna">Luna</option>
                <option value="Bella">Bella</option>
              </select>
            </div>

            <div className="grid grid-cols-2 gap-4">
              <div className="space-y-2">
                <Label htmlFor="temperature">Suhu Tubuh (°C)</Label>
                <Input
                  id="temperature"
                  type="number"
                  step="0.1"
                  placeholder="38.5"
                  value={healthData.temperature}
                  onChange={(e) => setHealthData({ ...healthData, temperature: e.target.value })}
                />
              </div>

              <div className="space-y-2">
                <Label htmlFor="heartRate">Detak Jantung (bpm)</Label>
                <Input
                  id="heartRate"
                  type="number"
                  placeholder="140"
                  value={healthData.heartRate}
                  onChange={(e) => setHealthData({ ...healthData, heartRate: e.target.value })}
                />
              </div>
            </div>

            <div className="space-y-2">
              <Label htmlFor="diagnosis">Diagnosis</Label>
              <Textarea
                id="diagnosis"
                placeholder="Kondisi kesehatan normal, tidak ada keluhan"
                value={healthData.diagnosis}
                onChange={(e) => setHealthData({ ...healthData, diagnosis: e.target.value })}
              />
            </div>

            <div className="space-y-2">
              <Label htmlFor="notes">Catatan Tambahan</Label>
              <Textarea
                id="notes"
                placeholder="Rekomendasi perawatan atau tindakan selanjutnya"
                value={healthData.notes}
                onChange={(e) => setHealthData({ ...healthData, notes: e.target.value })}
              />
            </div>

            <div className="flex gap-2">
              <Button variant="outline" onClick={() => setShowHealthForm(false)} className="flex-1">
                Batal
              </Button>
              <Button onClick={handleSubmitHealth} className="flex-1">
                Simpan & Kirim Notifikasi
              </Button>
            </div>
          </CardContent>
        </Card>
      )}

      {/* Today's Appointments */}
      <Card>
        <CardHeader>
          <CardTitle>Jadwal Hari Ini</CardTitle>
        </CardHeader>
        <CardContent>
          <div className="space-y-3">
            {appointments.map((appointment) => (
              <div
                key={appointment.id}
                className={`flex items-center gap-4 p-4 rounded-lg border-2 ${
                  appointment.status === 'in-progress'
                    ? 'bg-blue-50 border-blue-200'
                    : 'bg-white border-gray-200'
                }`}
              >
                <div className="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                  {appointment.type === 'Vaksinasi' ? (
                    <Syringe className="w-6 h-6 text-blue-600" />
                  ) : (
                    <Heart className="w-6 h-6 text-blue-600" />
                  )}
                </div>
                <div className="flex-1">
                  <div className="flex items-center gap-2 mb-1">
                    <span>{appointment.type}</span>
                    <span className="text-sm text-gray-600">• {appointment.pet}</span>
                  </div>
                  <p className="text-sm text-gray-600">{appointment.time}</p>
                </div>
                <Badge
                  variant="outline"
                  className={
                    appointment.status === 'in-progress'
                      ? 'bg-blue-50 text-blue-700 border-blue-200'
                      : 'bg-gray-50 text-gray-700 border-gray-200'
                  }
                >
                  {appointment.status === 'in-progress' ? 'Sedang Berlangsung' : 'Dijadwalkan'}
                </Badge>
              </div>
            ))}
          </div>
        </CardContent>
      </Card>

      {/* Medical Records */}
      <Card>
        <CardHeader>
          <CardTitle>Riwayat Medis Terbaru</CardTitle>
        </CardHeader>
        <CardContent>
          <div className="space-y-4">
            {[
              {
                pet: 'Luna',
                date: '2025-11-09',
                type: 'Vaksinasi',
                diagnosis: 'Vaksin rabies berhasil diberikan',
                status: 'Selesai',
              },
              {
                pet: 'Max',
                date: '2025-11-08',
                type: 'Cek Rutin',
                diagnosis: 'Kondisi kesehatan baik, suhu normal',
                status: 'Selesai',
              },
              {
                pet: 'Bella',
                date: '2025-11-07',
                type: 'Konsultasi',
                diagnosis: 'Nafsu makan menurun, diberi vitamin',
                status: 'Follow-up',
              },
            ].map((record, index) => (
              <div key={index} className="p-4 bg-gray-50 rounded-lg">
                <div className="flex items-start justify-between mb-2">
                  <div>
                    <h4 className="mb-1">{record.pet} - {record.type}</h4>
                    <p className="text-sm text-gray-600">{record.date}</p>
                  </div>
                  <Badge
                    variant="outline"
                    className={
                      record.status === 'Selesai'
                        ? 'bg-green-50 text-green-700 border-green-200'
                        : 'bg-yellow-50 text-yellow-700 border-yellow-200'
                    }
                  >
                    {record.status}
                  </Badge>
                </div>
                <p className="text-sm text-gray-700">{record.diagnosis}</p>
              </div>
            ))}
          </div>
        </CardContent>
      </Card>
    </div>
  );
}
