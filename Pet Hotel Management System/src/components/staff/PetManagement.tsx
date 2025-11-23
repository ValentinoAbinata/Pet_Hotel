import { useState } from 'react';
import { Card, CardContent, CardHeader, CardTitle } from '../ui/card';
import { Input } from '../ui/input';
import { Button } from '../ui/button';
import { Badge } from '../ui/badge';
import { Search, Filter, Eye, Edit, FileText } from 'lucide-react';

const pets = [
  {
    id: '1',
    name: 'Luna',
    type: 'Kucing Persian',
    owner: 'Sarah Pemilik',
    room: 'A-101',
    checkIn: '2025-11-08',
    checkOut: '2025-11-12',
    status: 'Menginap',
    health: 'Sehat',
    image: 'https://images.unsplash.com/photo-1573865526739-10c1d3a1f0cc?w=300',
  },
  {
    id: '2',
    name: 'Max',
    type: 'Anjing Golden Retriever',
    owner: 'John Doe',
    room: 'B-205',
    checkIn: '2025-11-09',
    checkOut: '2025-11-14',
    status: 'Menginap',
    health: 'Sehat',
    image: 'https://images.unsplash.com/photo-1587300003388-59208cc962cb?w=300',
  },
  {
    id: '3',
    name: 'Bella',
    type: 'Kelinci Holland Lop',
    owner: 'Jane Smith',
    room: 'C-310',
    checkIn: '2025-11-10',
    checkOut: '2025-11-13',
    status: 'Menginap',
    health: 'Perlu Perhatian',
    image: 'https://images.unsplash.com/photo-1585110396000-c9ffd4e4b308?w=300',
  },
  {
    id: '4',
    name: 'Charlie',
    type: 'Anjing Beagle',
    owner: 'Bob Wilson',
    room: '-',
    checkIn: '2025-11-15',
    checkOut: '2025-11-20',
    status: 'Terjadwal',
    health: '-',
    image: 'https://images.unsplash.com/photo-1505628346881-b72b27e84530?w=300',
  },
];

export function PetManagement() {
  const [searchQuery, setSearchQuery] = useState('');
  const [filterStatus, setFilterStatus] = useState('all');
  const [selectedPet, setSelectedPet] = useState<typeof pets[0] | null>(null);

  const filteredPets = pets.filter(pet => {
    const matchesSearch = pet.name.toLowerCase().includes(searchQuery.toLowerCase()) ||
                         pet.owner.toLowerCase().includes(searchQuery.toLowerCase());
    const matchesFilter = filterStatus === 'all' || pet.status === filterStatus;
    return matchesSearch && matchesFilter;
  });

  return (
    <div className="p-4 lg:p-8 space-y-6">
      <div>
        <h1 className="text-2xl lg:text-3xl mb-2">Manajemen Hewan</h1>
        <p className="text-gray-600">Kelola data dan profil hewan peliharaan</p>
      </div>

      {/* Search and Filter */}
      <Card>
        <CardContent className="pt-6">
          <div className="flex flex-col lg:flex-row gap-4">
            <div className="flex-1 relative">
              <Search className="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" />
              <Input
                placeholder="Cari berdasarkan nama hewan atau pemilik..."
                value={searchQuery}
                onChange={(e) => setSearchQuery(e.target.value)}
                className="pl-10"
              />
            </div>
            <div className="flex gap-2">
              <select
                className="px-4 py-2 border rounded-lg"
                value={filterStatus}
                onChange={(e) => setFilterStatus(e.target.value)}
              >
                <option value="all">Semua Status</option>
                <option value="Menginap">Menginap</option>
                <option value="Terjadwal">Terjadwal</option>
                <option value="Check-out">Check-out</option>
              </select>
              <Button variant="outline">
                <Filter className="w-4 h-4 mr-2" />
                Filter
              </Button>
            </div>
          </div>
        </CardContent>
      </Card>

      {/* Pet List */}
      <div className="grid grid-cols-1 lg:grid-cols-2 gap-4">
        {filteredPets.map((pet) => (
          <Card key={pet.id} className="overflow-hidden">
            <div className="flex flex-col lg:flex-row gap-4 p-4">
              <img
                src={pet.image}
                alt={pet.name}
                className="w-full lg:w-32 h-48 lg:h-32 object-cover rounded-lg"
              />
              <div className="flex-1 space-y-3">
                <div>
                  <div className="flex items-start justify-between mb-2">
                    <div>
                      <h3 className="text-lg">{pet.name}</h3>
                      <p className="text-sm text-gray-600">{pet.type}</p>
                    </div>
                    <Badge
                      variant="outline"
                      className={
                        pet.status === 'Menginap'
                          ? 'bg-green-50 text-green-700 border-green-200'
                          : 'bg-blue-50 text-blue-700 border-blue-200'
                      }
                    >
                      {pet.status}
                    </Badge>
                  </div>
                  <div className="space-y-1 text-sm text-gray-600">
                    <p>Pemilik: {pet.owner}</p>
                    {pet.room !== '-' && <p>Kamar: {pet.room}</p>}
                    <p>Check-in: {pet.checkIn}</p>
                    <p>Check-out: {pet.checkOut}</p>
                    {pet.health !== '-' && (
                      <div className="flex items-center gap-2">
                        <span>Status:</span>
                        <Badge
                          variant="outline"
                          className={
                            pet.health === 'Sehat'
                              ? 'bg-green-50 text-green-700 border-green-200'
                              : 'bg-yellow-50 text-yellow-700 border-yellow-200'
                          }
                        >
                          {pet.health}
                        </Badge>
                      </div>
                    )}
                  </div>
                </div>
                <div className="flex gap-2">
                  <Button 
                    size="sm" 
                    variant="outline" 
                    onClick={() => setSelectedPet(pet)}
                    className="flex-1"
                  >
                    <Eye className="w-4 h-4 mr-1" />
                    Detail
                  </Button>
                  <Button size="sm" variant="outline" className="flex-1">
                    <Edit className="w-4 h-4 mr-1" />
                    Edit
                  </Button>
                </div>
              </div>
            </div>
          </Card>
        ))}
      </div>

      {/* Pet Detail Modal */}
      {selectedPet && (
        <div className="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
          <Card className="w-full max-w-2xl max-h-[90vh] overflow-y-auto">
            <CardHeader>
              <div className="flex items-start justify-between">
                <CardTitle>Profil Lengkap - {selectedPet.name}</CardTitle>
                <Button
                  variant="ghost"
                  size="sm"
                  onClick={() => setSelectedPet(null)}
                >
                  ✕
                </Button>
              </div>
            </CardHeader>
            <CardContent className="space-y-6">
              <div className="flex flex-col lg:flex-row gap-6">
                <img
                  src={selectedPet.image}
                  alt={selectedPet.name}
                  className="w-full lg:w-48 h-48 object-cover rounded-lg"
                />
                <div className="flex-1 space-y-3">
                  <div>
                    <h3 className="text-lg mb-1">{selectedPet.name}</h3>
                    <p className="text-gray-600">{selectedPet.type}</p>
                  </div>
                  <div className="space-y-2 text-sm">
                    <div className="flex justify-between">
                      <span className="text-gray-600">Pemilik:</span>
                      <span>{selectedPet.owner}</span>
                    </div>
                    <div className="flex justify-between">
                      <span className="text-gray-600">Kamar:</span>
                      <span>{selectedPet.room}</span>
                    </div>
                    <div className="flex justify-between">
                      <span className="text-gray-600">Check-in:</span>
                      <span>{selectedPet.checkIn}</span>
                    </div>
                    <div className="flex justify-between">
                      <span className="text-gray-600">Check-out:</span>
                      <span>{selectedPet.checkOut}</span>
                    </div>
                    <div className="flex justify-between items-center">
                      <span className="text-gray-600">Status:</span>
                      <Badge variant="outline" className="bg-green-50 text-green-700 border-green-200">
                        {selectedPet.status}
                      </Badge>
                    </div>
                  </div>
                </div>
              </div>

              <div className="border-t pt-4 space-y-4">
                <h4>Riwayat Medis</h4>
                <div className="space-y-2">
                  <div className="p-3 bg-gray-50 rounded-lg">
                    <div className="flex justify-between mb-1">
                      <span>Vaksinasi Rabies</span>
                      <span className="text-sm text-gray-600">2025-11-09</span>
                    </div>
                    <p className="text-sm text-gray-600">Berhasil diberikan, tidak ada efek samping</p>
                  </div>
                  <div className="p-3 bg-gray-50 rounded-lg">
                    <div className="flex justify-between mb-1">
                      <span>Cek Kesehatan Rutin</span>
                      <span className="text-sm text-gray-600">2025-11-08</span>
                    </div>
                    <p className="text-sm text-gray-600">Kondisi baik, suhu 38.5°C</p>
                  </div>
                </div>
              </div>

              <div className="border-t pt-4 space-y-4">
                <h4>Riwayat Menginap</h4>
                <div className="space-y-2">
                  <div className="p-3 bg-gray-50 rounded-lg">
                    <div className="flex justify-between">
                      <span>08 Nov - 12 Nov 2025</span>
                      <Badge variant="outline">4 hari</Badge>
                    </div>
                  </div>
                  <div className="p-3 bg-gray-50 rounded-lg">
                    <div className="flex justify-between">
                      <span>01 Okt - 05 Okt 2025</span>
                      <Badge variant="outline">4 hari</Badge>
                    </div>
                  </div>
                </div>
              </div>

              <div className="flex gap-2">
                <Button className="flex-1">
                  <FileText className="w-4 h-4 mr-2" />
                  Unduh Laporan
                </Button>
                <Button variant="outline" className="flex-1">
                  <Edit className="w-4 h-4 mr-2" />
                  Edit Profil
                </Button>
              </div>
            </CardContent>
          </Card>
        </div>
      )}
    </div>
  );
}
