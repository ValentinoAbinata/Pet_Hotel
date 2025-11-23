import { Card, CardContent, CardHeader, CardTitle } from '../ui/card';
import { Button } from '../ui/button';
import { TrendingUp, Download, DollarSign, Users, PawPrint, Calendar } from 'lucide-react';
import { Badge } from '../ui/badge';
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
import { Line, Bar, Pie } from 'react-chartjs-2';

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

export function ReportsView() {
  // Revenue Trend Data
  const revenueData = {
    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt'],
    datasets: [
      {
        label: 'Pendapatan 2025 (Juta Rp)',
        data: [12, 19, 15, 25, 22, 30, 28, 35, 32, 40],
        borderColor: 'rgb(59, 130, 246)',
        backgroundColor: 'rgba(59, 130, 246, 0.1)',
        tension: 0.4,
      },
      {
        label: 'Pendapatan 2024 (Juta Rp)',
        data: [10, 15, 13, 20, 18, 25, 23, 28, 26, 32],
        borderColor: 'rgb(156, 163, 175)',
        backgroundColor: 'rgba(156, 163, 175, 0.1)',
        tension: 0.4,
      },
    ],
  };

  // Service Performance Data
  const serviceData = {
    labels: ['Penitipan', 'Grooming', 'Vaksinasi', 'Cek Kesehatan', 'Konsultasi'],
    datasets: [
      {
        label: 'Jumlah Layanan',
        data: [156, 98, 45, 67, 34],
        backgroundColor: [
          'rgba(59, 130, 246, 0.7)',
          'rgba(34, 197, 94, 0.7)',
          'rgba(251, 146, 60, 0.7)',
          'rgba(168, 85, 247, 0.7)',
          'rgba(236, 72, 153, 0.7)',
        ],
      },
    ],
  };

  // Pet Type Distribution
  const petTypeData = {
    labels: ['Anjing', 'Kucing', 'Kelinci', 'Burung', 'Lainnya'],
    datasets: [
      {
        data: [45, 35, 12, 5, 3],
        backgroundColor: [
          'rgba(59, 130, 246, 0.7)',
          'rgba(34, 197, 94, 0.7)',
          'rgba(251, 146, 60, 0.7)',
          'rgba(168, 85, 247, 0.7)',
          'rgba(236, 72, 153, 0.7)',
        ],
      },
    ],
  };

  return (
    <div className="p-4 lg:p-8 space-y-6">
      <div className="flex items-center justify-between">
        <div>
          <h1 className="text-2xl lg:text-3xl mb-2">Laporan Digital</h1>
          <p className="text-gray-600">Analitik dan performa Pet Hotel</p>
        </div>
        <Button>
          <Download className="w-4 h-4 mr-2" />
          Unduh Laporan
        </Button>
      </div>

      {/* Period Selector */}
      <Card>
        <CardContent className="pt-6">
          <div className="flex flex-wrap gap-2">
            <Button variant="outline" size="sm">Hari Ini</Button>
            <Button variant="outline" size="sm">Minggu Ini</Button>
            <Button size="sm">Bulan Ini</Button>
            <Button variant="outline" size="sm">Tahun Ini</Button>
            <Button variant="outline" size="sm">Custom</Button>
          </div>
        </CardContent>
      </Card>

      {/* Key Metrics */}
      <div className="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <Card>
          <CardContent className="pt-6">
            <div className="flex items-center gap-3">
              <div className="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <DollarSign className="w-6 h-6 text-blue-600" />
              </div>
              <div>
                <p className="text-sm text-gray-600">Pendapatan</p>
                <p className="text-2xl">Rp 40M</p>
                <div className="flex items-center gap-1 text-xs text-green-600">
                  <TrendingUp className="w-3 h-3" />
                  <span>+25%</span>
                </div>
              </div>
            </div>
          </CardContent>
        </Card>

        <Card>
          <CardContent className="pt-6">
            <div className="flex items-center gap-3">
              <div className="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                <PawPrint className="w-6 h-6 text-green-600" />
              </div>
              <div>
                <p className="text-sm text-gray-600">Total Hewan</p>
                <p className="text-2xl">156</p>
                <div className="flex items-center gap-1 text-xs text-green-600">
                  <TrendingUp className="w-3 h-3" />
                  <span>+12%</span>
                </div>
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
                <p className="text-2xl">248</p>
                <div className="flex items-center gap-1 text-xs text-green-600">
                  <TrendingUp className="w-3 h-3" />
                  <span>+18%</span>
                </div>
              </div>
            </div>
          </CardContent>
        </Card>

        <Card>
          <CardContent className="pt-6">
            <div className="flex items-center gap-3">
              <div className="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                <Calendar className="w-6 h-6 text-purple-600" />
              </div>
              <div>
                <p className="text-sm text-gray-600">Reservasi</p>
                <p className="text-2xl">89</p>
                <div className="flex items-center gap-1 text-xs text-green-600">
                  <TrendingUp className="w-3 h-3" />
                  <span>+8%</span>
                </div>
              </div>
            </div>
          </CardContent>
        </Card>
      </div>

      {/* Revenue Trend Chart */}
      <Card>
        <CardHeader>
          <div className="flex items-center justify-between">
            <CardTitle className="flex items-center gap-2">
              <TrendingUp className="w-5 h-5" />
              Tren Pendapatan
            </CardTitle>
            <Badge className="bg-green-100 text-green-700 hover:bg-green-100">
              +25% dari tahun lalu
            </Badge>
          </div>
        </CardHeader>
        <CardContent>
          <Line 
            data={revenueData} 
            options={{ 
              responsive: true, 
              maintainAspectRatio: true,
              plugins: {
                legend: {
                  position: 'bottom',
                },
              },
            }} 
          />
        </CardContent>
      </Card>

      {/* Service Performance and Pet Distribution */}
      <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <Card>
          <CardHeader>
            <CardTitle>Kinerja Layanan</CardTitle>
          </CardHeader>
          <CardContent>
            <Bar 
              data={serviceData} 
              options={{ 
                responsive: true, 
                maintainAspectRatio: true,
                plugins: {
                  legend: {
                    display: false,
                  },
                },
              }} 
            />
          </CardContent>
        </Card>

        <Card>
          <CardHeader>
            <CardTitle>Distribusi Jenis Hewan</CardTitle>
          </CardHeader>
          <CardContent>
            <Pie 
              data={petTypeData} 
              options={{ 
                responsive: true, 
                maintainAspectRatio: true,
                plugins: {
                  legend: {
                    position: 'bottom',
                  },
                },
              }} 
            />
          </CardContent>
        </Card>
      </div>

      {/* Top Performing Services */}
      <Card>
        <CardHeader>
          <CardTitle>Layanan Terpopuler</CardTitle>
        </CardHeader>
        <CardContent>
          <div className="space-y-4">
            {[
              { name: 'Penitipan Standard', count: 156, revenue: 'Rp 15.6M', growth: '+15%' },
              { name: 'Grooming Premium', count: 98, revenue: 'Rp 14.7M', growth: '+22%' },
              { name: 'Penitipan Suite', count: 67, revenue: 'Rp 23.4M', growth: '+30%' },
              { name: 'Vaksinasi', count: 45, revenue: 'Rp 9M', growth: '+8%' },
              { name: 'Cek Kesehatan', count: 67, revenue: 'Rp 6.7M', growth: '+12%' },
            ].map((service, index) => (
              <div key={index} className="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                <div className="flex items-center gap-4">
                  <div className="text-2xl text-gray-400">#{index + 1}</div>
                  <div>
                    <h4>{service.name}</h4>
                    <p className="text-sm text-gray-600">{service.count} transaksi</p>
                  </div>
                </div>
                <div className="text-right">
                  <p>{service.revenue}</p>
                  <p className="text-sm text-green-600">{service.growth}</p>
                </div>
              </div>
            ))}
          </div>
        </CardContent>
      </Card>

      {/* Customer Satisfaction */}
      <Card>
        <CardHeader>
          <CardTitle>Kepuasan Pelanggan</CardTitle>
        </CardHeader>
        <CardContent>
          <div className="grid grid-cols-1 lg:grid-cols-3 gap-4">
            <div className="p-6 bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl text-center">
              <div className="text-4xl mb-2">4.8</div>
              <p className="text-gray-600">Rating Rata-rata</p>
              <div className="flex justify-center gap-1 mt-2">
                {[1, 2, 3, 4, 5].map((star) => (
                  <span key={star} className="text-yellow-500">★</span>
                ))}
              </div>
            </div>
            <div className="p-6 bg-gradient-to-br from-blue-50 to-cyan-50 rounded-xl text-center">
              <div className="text-4xl mb-2">95%</div>
              <p className="text-gray-600">Tingkat Kepuasan</p>
              <p className="text-sm text-gray-500 mt-2">236 dari 248 pelanggan</p>
            </div>
            <div className="p-6 bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl text-center">
              <div className="text-4xl mb-2">87%</div>
              <p className="text-gray-600">Pelanggan Berulang</p>
              <p className="text-sm text-gray-500 mt-2">216 pelanggan kembali</p>
            </div>
          </div>
        </CardContent>
      </Card>
    </div>
  );
}
