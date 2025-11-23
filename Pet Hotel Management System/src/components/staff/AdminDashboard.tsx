import { Card, CardContent, CardHeader, CardTitle } from '../ui/card';
import { Users, Calendar, DollarSign, TrendingUp, PawPrint, Building } from 'lucide-react';
import { Badge } from '../ui/badge';
import { ScheduleCalendar } from './ScheduleCalendar';
import { PetManagement } from './PetManagement';
import { ReportsView } from './ReportsView';
import { SettingsView } from './SettingsView';
import {
  Chart as ChartJS,
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  BarElement,
  Title,
  Tooltip,
  Legend,
  ArcElement,
} from 'chart.js';
import { Line, Bar, Doughnut } from 'react-chartjs-2';

ChartJS.register(
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  BarElement,
  Title,
  Tooltip,
  Legend,
  ArcElement
);

interface AdminDashboardProps {
  activeTab: string;
}

export function AdminDashboard({ activeTab }: AdminDashboardProps) {
  if (activeTab === 'schedule') {
    return <ScheduleCalendar />;
  }

  if (activeTab === 'pets') {
    return <PetManagement />;
  }

  if (activeTab === 'reports') {
    return <ReportsView />;
  }

  if (activeTab === 'settings') {
    return <SettingsView />;
  }

  // Dashboard view
  const revenueData = {
    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
    datasets: [
      {
        label: 'Pendapatan (Juta Rp)',
        data: [12, 19, 15, 25, 22, 30],
        borderColor: 'rgb(59, 130, 246)',
        backgroundColor: 'rgba(59, 130, 246, 0.1)',
        tension: 0.4,
      },
    ],
  };

  const occupancyData = {
    labels: ['Minggu 1', 'Minggu 2', 'Minggu 3', 'Minggu 4'],
    datasets: [
      {
        label: 'Tingkat Hunian (%)',
        data: [75, 82, 88, 95],
        backgroundColor: 'rgba(34, 197, 94, 0.7)',
      },
    ],
  };

  const serviceDistribution = {
    labels: ['Penitipan', 'Grooming', 'Vaksinasi', 'Lainnya'],
    datasets: [
      {
        data: [45, 30, 15, 10],
        backgroundColor: [
          'rgba(59, 130, 246, 0.7)',
          'rgba(34, 197, 94, 0.7)',
          'rgba(251, 146, 60, 0.7)',
          'rgba(168, 85, 247, 0.7)',
        ],
      },
    ],
  };

  return (
    <div className="p-4 lg:p-8 space-y-6">
      <div>
        <h1 className="text-2xl lg:text-3xl mb-2">Dashboard Admin</h1>
        <p className="text-gray-600">Ringkasan dan analitik Pet Hotel</p>
      </div>

      {/* Key Metrics */}
      <div className="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <Card>
          <CardContent className="pt-6">
            <div className="flex items-center gap-3">
              <div className="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <PawPrint className="w-6 h-6 text-blue-600" />
              </div>
              <div>
                <p className="text-sm text-gray-600">Total Hewan</p>
                <p className="text-2xl">48</p>
              </div>
            </div>
          </CardContent>
        </Card>

        <Card>
          <CardContent className="pt-6">
            <div className="flex items-center gap-3">
              <div className="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                <Building className="w-6 h-6 text-green-600" />
              </div>
              <div>
                <p className="text-sm text-gray-600">Okupansi</p>
                <p className="text-2xl">95%</p>
              </div>
            </div>
          </CardContent>
        </Card>

        <Card>
          <CardContent className="pt-6">
            <div className="flex items-center gap-3">
              <div className="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                <Users className="w-6 h-6 text-orange-600" />
              </div>
              <div>
                <p className="text-sm text-gray-600">Pelanggan</p>
                <p className="text-2xl">156</p>
              </div>
            </div>
          </CardContent>
        </Card>

        <Card>
          <CardContent className="pt-6">
            <div className="flex items-center gap-3">
              <div className="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                <DollarSign className="w-6 h-6 text-purple-600" />
              </div>
              <div>
                <p className="text-sm text-gray-600">Pendapatan</p>
                <p className="text-2xl">30M</p>
              </div>
            </div>
          </CardContent>
        </Card>
      </div>

      {/* Charts */}
      <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <Card>
          <CardHeader>
            <CardTitle className="flex items-center gap-2">
              <TrendingUp className="w-5 h-5" />
              Tren Pendapatan
            </CardTitle>
          </CardHeader>
          <CardContent>
            <Line data={revenueData} options={{ responsive: true, maintainAspectRatio: true }} />
          </CardContent>
        </Card>

        <Card>
          <CardHeader>
            <CardTitle>Tingkat Hunian Bulanan</CardTitle>
          </CardHeader>
          <CardContent>
            <Bar data={occupancyData} options={{ responsive: true, maintainAspectRatio: true }} />
          </CardContent>
        </Card>
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <Card>
          <CardHeader>
            <CardTitle>Distribusi Layanan</CardTitle>
          </CardHeader>
          <CardContent>
            <Doughnut data={serviceDistribution} options={{ responsive: true, maintainAspectRatio: true }} />
          </CardContent>
        </Card>

        <Card className="lg:col-span-2">
          <CardHeader>
            <CardTitle>Reservasi Terbaru</CardTitle>
          </CardHeader>
          <CardContent>
            <div className="space-y-3">
              {[
                { pet: 'Max', owner: 'John Doe', date: '2025-11-15', status: 'Dikonfirmasi' },
                { pet: 'Bella', owner: 'Jane Smith', date: '2025-11-16', status: 'Pending' },
                { pet: 'Charlie', owner: 'Bob Wilson', date: '2025-11-17', status: 'Dikonfirmasi' },
                { pet: 'Daisy', owner: 'Alice Brown', date: '2025-11-18', status: 'Dikonfirmasi' },
              ].map((booking, index) => (
                <div key={index} className="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                  <div className="flex items-center gap-3">
                    <div className="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                      <PawPrint className="w-5 h-5 text-blue-600" />
                    </div>
                    <div>
                      <p>{booking.pet}</p>
                      <p className="text-sm text-gray-600">{booking.owner}</p>
                    </div>
                  </div>
                  <div className="text-right">
                    <p className="text-sm text-gray-600">{booking.date}</p>
                    <Badge
                      variant="outline"
                      className={
                        booking.status === 'Dikonfirmasi'
                          ? 'bg-green-50 text-green-700 border-green-200'
                          : 'bg-yellow-50 text-yellow-700 border-yellow-200'
                      }
                    >
                      {booking.status}
                    </Badge>
                  </div>
                </div>
              ))}
            </div>
          </CardContent>
        </Card>
      </div>

      {/* Staff Overview */}
      <Card>
        <CardHeader>
          <CardTitle>Tim Staf</CardTitle>
        </CardHeader>
        <CardContent>
          <div className="grid grid-cols-1 lg:grid-cols-4 gap-4">
            {[
              { name: 'Dr. Rina', role: 'Dokter Hewan', status: 'Aktif', avatar: 'R' },
              { name: 'Budi Santoso', role: 'Staf Perawatan', status: 'Aktif', avatar: 'B' },
              { name: 'Siti Aminah', role: 'Staf Perawatan', status: 'Aktif', avatar: 'S' },
              { name: 'Ahmad Yani', role: 'Resepsionis', status: 'Cuti', avatar: 'A' },
            ].map((staff, index) => (
              <div key={index} className="p-4 border rounded-lg">
                <div className="flex items-center gap-3 mb-2">
                  <div className="w-10 h-10 bg-gradient-to-br from-blue-500 to-green-500 rounded-full flex items-center justify-center text-white">
                    {staff.avatar}
                  </div>
                  <div className="flex-1 min-w-0">
                    <p className="truncate">{staff.name}</p>
                    <p className="text-sm text-gray-600">{staff.role}</p>
                  </div>
                </div>
                <Badge
                  variant="outline"
                  className={
                    staff.status === 'Aktif'
                      ? 'bg-green-50 text-green-700 border-green-200'
                      : 'bg-gray-50 text-gray-700 border-gray-200'
                  }
                >
                  {staff.status}
                </Badge>
              </div>
            ))}
          </div>
        </CardContent>
      </Card>
    </div>
  );
}
