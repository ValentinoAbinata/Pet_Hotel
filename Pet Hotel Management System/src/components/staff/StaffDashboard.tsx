import { useState } from 'react';
import { User } from '../../App';
import { AdminDashboard } from './AdminDashboard';
import { CaretakerDashboard } from './CaretakerDashboard';
import { VetDashboard } from './VetDashboard';
import { LayoutDashboard, Calendar, FileText, Users, Settings, LogOut, Menu, X } from 'lucide-react';
import { Button } from '../ui/button';

interface StaffDashboardProps {
  user: User;
  onLogout: () => void;
}

type StaffTab = 'dashboard' | 'schedule' | 'pets' | 'reports' | 'settings';

export function StaffDashboard({ user, onLogout }: StaffDashboardProps) {
  const [activeTab, setActiveTab] = useState<StaffTab>('dashboard');
  const [mobileMenuOpen, setMobileMenuOpen] = useState(false);

  const getTabsForRole = () => {
    const commonTabs = [
      { id: 'dashboard' as StaffTab, label: 'Dashboard', icon: LayoutDashboard },
      { id: 'schedule' as StaffTab, label: 'Jadwal', icon: Calendar },
    ];

    if (user.role === 'admin') {
      return [
        ...commonTabs,
        { id: 'reports' as StaffTab, label: 'Laporan', icon: FileText },
        { id: 'pets' as StaffTab, label: 'Hewan', icon: Users },
        { id: 'settings' as StaffTab, label: 'Pengaturan', icon: Settings },
      ];
    }

    return [
      ...commonTabs,
      { id: 'pets' as StaffTab, label: 'Hewan', icon: Users },
    ];
  };

  const tabs = getTabsForRole();

  const getRoleLabel = () => {
    switch (user.role) {
      case 'admin':
        return 'Administrator';
      case 'caretaker':
        return 'Staf Perawatan';
      case 'vet':
        return 'Dokter Hewan';
      default:
        return '';
    }
  };

  return (
    <div className="min-h-screen bg-gray-50">
      {/* Header */}
      <div className="bg-white border-b sticky top-0 z-50">
        <div className="flex items-center justify-between p-4">
          <div>
            <h1 className="text-lg lg:text-xl bg-gradient-to-r from-blue-600 to-green-600 bg-clip-text text-transparent">
              Pet Hotel Staff
            </h1>
            <p className="text-sm text-gray-600">{getRoleLabel()}</p>
          </div>
          <div className="flex items-center gap-2">
            <span className="hidden lg:inline text-sm text-gray-600">
              {user.name}
            </span>
            <Button 
              variant="ghost" 
              size="sm"
              onClick={() => setMobileMenuOpen(!mobileMenuOpen)}
              className="lg:hidden"
            >
              {mobileMenuOpen ? <X className="w-5 h-5" /> : <Menu className="w-5 h-5" />}
            </Button>
            <Button 
              variant="outline" 
              size="sm" 
              onClick={onLogout}
              className="hidden lg:flex"
            >
              <LogOut className="w-4 h-4 mr-2" />
              Keluar
            </Button>
          </div>
        </div>
        
        {mobileMenuOpen && (
          <div className="border-t p-4 lg:hidden">
            <Button 
              variant="outline" 
              size="sm" 
              onClick={onLogout}
              className="w-full"
            >
              <LogOut className="w-4 h-4 mr-2" />
              Keluar
            </Button>
          </div>
        )}
      </div>

      <div className="flex">
        {/* Sidebar */}
        <div className="hidden lg:block w-64 bg-white border-r min-h-screen sticky top-16">
          <nav className="p-4 space-y-1">
            {tabs.map((tab) => {
              const Icon = tab.icon;
              return (
                <button
                  key={tab.id}
                  onClick={() => setActiveTab(tab.id)}
                  className={`w-full flex items-center gap-3 px-4 py-3 rounded-lg transition-colors ${
                    activeTab === tab.id
                      ? 'bg-blue-50 text-blue-600'
                      : 'text-gray-700 hover:bg-gray-50'
                  }`}
                >
                  <Icon className="w-5 h-5" />
                  <span>{tab.label}</span>
                </button>
              );
            })}
          </nav>
        </div>

        {/* Main Content */}
        <div className="flex-1 pb-20 lg:pb-0">
          {user.role === 'admin' && <AdminDashboard activeTab={activeTab} />}
          {user.role === 'caretaker' && <CaretakerDashboard activeTab={activeTab} />}
          {user.role === 'vet' && <VetDashboard activeTab={activeTab} />}
        </div>
      </div>

      {/* Mobile Bottom Navigation */}
      <div className="lg:hidden fixed bottom-0 left-0 right-0 bg-white border-t z-40">
        <div className="grid grid-cols-4 gap-1 p-2">
          {tabs.slice(0, 4).map((tab) => {
            const Icon = tab.icon;
            return (
              <button
                key={tab.id}
                onClick={() => {
                  setActiveTab(tab.id);
                  setMobileMenuOpen(false);
                }}
                className={`flex flex-col items-center gap-1 py-2 rounded-lg transition-colors ${
                  activeTab === tab.id
                    ? 'text-blue-600'
                    : 'text-gray-600'
                }`}
              >
                <Icon className="w-5 h-5" />
                <span className="text-xs">{tab.label}</span>
              </button>
            );
          })}
        </div>
      </div>
    </div>
  );
}
