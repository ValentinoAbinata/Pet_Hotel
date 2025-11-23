import { useState } from 'react';
import { Card, CardContent, CardHeader, CardTitle } from '../ui/card';
import { Button } from '../ui/button';
import { Input } from '../ui/input';
import { Label } from '../ui/label';
import { Textarea } from '../ui/textarea';
import { Badge } from '../ui/badge';
import { CheckCircle, Clock, PawPrint, Utensils, Activity } from 'lucide-react';
import { ScheduleCalendar } from './ScheduleCalendar';
import { PetManagement } from './PetManagement';

interface CaretakerDashboardProps {
  activeTab: string;
}

const todayTasks = [
  { id: '1', pet: 'Luna', task: 'Pemberian Makan Pagi', time: '07:00', status: 'completed' },
  { id: '2', pet: 'Max', task: 'Pemberian Makan Pagi', time: '07:00', status: 'completed' },
  { id: '3', pet: 'Luna', task: 'Pemberian Makan Siang', time: '12:00', status: 'pending' },
  { id: '4', pet: 'Max', task: 'Grooming', time: '14:00', status: 'pending' },
  { id: '5', pet: 'Bella', task: 'Waktu Bermain', time: '15:00', status: 'pending' },
];

export function CaretakerDashboard({ activeTab }: CaretakerDashboardProps) {
  const [showFeedingForm, setShowFeedingForm] = useState(false);
  const [feedingData, setFeedingData] = useState({
    pet: 'Luna',
    amount: '',
    notes: '',
  });

  if (activeTab === 'schedule') {
    return <ScheduleCalendar />;
  }

  if (activeTab === 'pets') {
    return <PetManagement />;
  }

  const handleSubmitFeeding = () => {
    alert('Data makan berhasil disimpan dan notifikasi dikirim ke pemilik!');
    setShowFeedingForm(false);
    setFeedingData({ pet: 'Luna', amount: '', notes: '' });
  };

  const completedTasks = todayTasks.filter(t => t.status === 'completed').length;
  const pendingTasks = todayTasks.filter(t => t.status === 'pending').length;

  return (
    <div className="p-4 lg:p-8 space-y-6">
      <div>
        <h1 className="text-2xl lg:text-3xl mb-2">Dashboard Staf Perawatan</h1>
        <p className="text-gray-600">Kelola perawatan harian hewan peliharaan</p>
      </div>

      {/* Task Summary */}
      <div className="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <Card>
          <CardContent className="pt-6">
            <div className="flex items-center gap-3">
              <div className="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                <CheckCircle className="w-6 h-6 text-green-600" />
              </div>
              <div>
                <p className="text-sm text-gray-600">Selesai</p>
                <p className="text-2xl">{completedTasks}</p>
              </div>
            </div>
          </CardContent>
        </Card>

        <Card>
          <CardContent className="pt-6">
            <div className="flex items-center gap-3">
              <div className="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                <Clock className="w-6 h-6 text-orange-600" />
              </div>
              <div>
                <p className="text-sm text-gray-600">Pending</p>
                <p className="text-2xl">{pendingTasks}</p>
              </div>
            </div>
          </CardContent>
        </Card>

        <Card>
          <CardContent className="pt-6">
            <div className="flex items-center gap-3">
              <div className="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <PawPrint className="w-6 h-6 text-blue-600" />
              </div>
              <div>
                <p className="text-sm text-gray-600">Hewan Aktif</p>
                <p className="text-2xl">12</p>
              </div>
            </div>
          </CardContent>
        </Card>

        <Card>
          <CardContent className="pt-6">
            <div className="flex items-center gap-3">
              <div className="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                <Activity className="w-6 h-6 text-purple-600" />
              </div>
              <div>
                <p className="text-sm text-gray-600">Aktivitas</p>
                <p className="text-2xl">8</p>
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
              onClick={() => setShowFeedingForm(true)}
              className="h-auto py-4 flex-col gap-2"
            >
              <Utensils className="w-6 h-6" />
              <span>Input Makan</span>
            </Button>
            <Button variant="outline" className="h-auto py-4 flex-col gap-2">
              <Activity className="w-6 h-6" />
              <span>Catat Aktivitas</span>
            </Button>
            <Button variant="outline" className="h-auto py-4 flex-col gap-2">
              <CheckCircle className="w-6 h-6" />
              <span>Check-in</span>
            </Button>
            <Button variant="outline" className="h-auto py-4 flex-col gap-2">
              <PawPrint className="w-6 h-6" />
              <span>Check-out</span>
            </Button>
          </div>
        </CardContent>
      </Card>

      {/* Feeding Form Modal */}
      {showFeedingForm && (
        <Card className="border-2 border-blue-500">
          <CardHeader>
            <CardTitle>Input Data Makan</CardTitle>
          </CardHeader>
          <CardContent className="space-y-4">
            <div className="space-y-2">
              <Label htmlFor="pet">Hewan Peliharaan</Label>
              <select
                id="pet"
                className="w-full px-3 py-2 border rounded-lg"
                value={feedingData.pet}
                onChange={(e) => setFeedingData({ ...feedingData, pet: e.target.value })}
              >
                <option value="Luna">Luna</option>
                <option value="Max">Max</option>
                <option value="Bella">Bella</option>
              </select>
            </div>

            <div className="space-y-2">
              <Label htmlFor="amount">Jumlah Makanan (gram)</Label>
              <Input
                id="amount"
                type="number"
                placeholder="100"
                value={feedingData.amount}
                onChange={(e) => setFeedingData({ ...feedingData, amount: e.target.value })}
              />
            </div>

            <div className="space-y-2">
              <Label htmlFor="notes">Catatan</Label>
              <Textarea
                id="notes"
                placeholder="Contoh: Habis semua, terlihat sangat lapar"
                value={feedingData.notes}
                onChange={(e) => setFeedingData({ ...feedingData, notes: e.target.value })}
              />
            </div>

            <div className="flex gap-2">
              <Button variant="outline" onClick={() => setShowFeedingForm(false)} className="flex-1">
                Batal
              </Button>
              <Button onClick={handleSubmitFeeding} className="flex-1">
                Simpan & Kirim Notifikasi
              </Button>
            </div>
          </CardContent>
        </Card>
      )}

      {/* Today's Tasks */}
      <Card>
        <CardHeader>
          <CardTitle>Jadwal Hari Ini</CardTitle>
        </CardHeader>
        <CardContent>
          <div className="space-y-3">
            {todayTasks.map((task) => (
              <div
                key={task.id}
                className={`flex items-center gap-4 p-4 rounded-lg border-2 ${
                  task.status === 'completed'
                    ? 'bg-green-50 border-green-200'
                    : 'bg-white border-gray-200'
                }`}
              >
                <div className="flex-shrink-0">
                  {task.status === 'completed' ? (
                    <CheckCircle className="w-6 h-6 text-green-600" />
                  ) : (
                    <Clock className="w-6 h-6 text-orange-600" />
                  )}
                </div>
                <div className="flex-1">
                  <div className="flex items-center gap-2 mb-1">
                    <span>{task.task}</span>
                    <span className="text-sm text-gray-600">• {task.pet}</span>
                  </div>
                  <p className="text-sm text-gray-600">{task.time}</p>
                </div>
                {task.status === 'pending' && (
                  <Button size="sm" variant="outline">
                    Tandai Selesai
                  </Button>
                )}
              </div>
            ))}
          </div>
        </CardContent>
      </Card>

      {/* Current Pets Under Care */}
      <Card>
        <CardHeader>
          <CardTitle>Hewan dalam Perawatan</CardTitle>
        </CardHeader>
        <CardContent>
          <div className="grid grid-cols-1 lg:grid-cols-2 gap-4">
            {[
              { name: 'Luna', type: 'Kucing', room: 'A-101', status: 'Sehat', image: 'https://images.unsplash.com/photo-1573865526739-10c1d3a1f0cc?w=200' },
              { name: 'Max', type: 'Anjing', room: 'B-205', status: 'Sehat', image: 'https://images.unsplash.com/photo-1587300003388-59208cc962cb?w=200' },
              { name: 'Bella', type: 'Kelinci', room: 'C-310', status: 'Perlu Perhatian', image: 'https://images.unsplash.com/photo-1585110396000-c9ffd4e4b308?w=200' },
            ].map((pet, index) => (
              <div key={index} className="flex gap-4 p-4 bg-gray-50 rounded-lg">
                <img
                  src={pet.image}
                  alt={pet.name}
                  className="w-20 h-20 object-cover rounded-lg"
                />
                <div className="flex-1">
                  <div className="flex items-start justify-between mb-2">
                    <div>
                      <h4>{pet.name}</h4>
                      <p className="text-sm text-gray-600">{pet.type}</p>
                    </div>
                    <Badge
                      variant="outline"
                      className={
                        pet.status === 'Sehat'
                          ? 'bg-green-50 text-green-700 border-green-200'
                          : 'bg-yellow-50 text-yellow-700 border-yellow-200'
                      }
                    >
                      {pet.status}
                    </Badge>
                  </div>
                  <p className="text-sm text-gray-600">Kamar: {pet.room}</p>
                </div>
              </div>
            ))}
          </div>
        </CardContent>
      </Card>
    </div>
  );
}
